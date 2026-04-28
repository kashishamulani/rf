<?php

namespace App\Imports;

use App\Models\Mobilization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Carbon\Carbon;

class DynamicHeaderMobilizationImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation,
    WithEvents,
    SkipsOnFailure
{
    use Importable;
    
    protected $headerMapping = [];
    protected $failures = [];
    protected $actualHeaders = [];
    protected $rowNumber = 0;
    public $successCount = 0;
    public $failureCount = 0;
    public $errorMessages = [];
    
    /**
     * Register events to capture and map headers before import
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                $worksheet = $event->getReader()->getActiveSheet();
                $headers = [];
                
                // Get first row as headers
                foreach ($worksheet->getRowIterator()->current()->getCellIterator() as $cell) {
                    $headers[] = $cell->getValue();
                }
                
                $this->actualHeaders = $headers;
                $this->headerMapping = $this->mapHeaders($headers);
                
                \Log::info('Detected headers:', $headers);
                \Log::info('Mapped headers:', $this->headerMapping);
            },
        ];
    }
    
    /**
     * Map actual headers to our expected field names
     */
    private function mapHeaders($headers)
    {
        $mapping = [];
        
        // Define possible header names for each field
        $fieldMap = [
            'name' => ['name', 'full_name', 'full name', 'candidate name', 'person name', 'employee name'],
            'email' => ['email', 'email_address', 'email address', 'email id', 'mail', 'e-mail'],
            'mobile' => ['mobile', 'contact_number', 'contact number', 'phone', 'phone no', 'contact'],
            'highest_qualification' => ['education', 'qualification', 'highest qualification', 'degree'],
            'dob' => ['dob', 'date_of_birth', 'date of birth', 'birth date'],
            'gender' => ['gender', 'sex'],
            'marital_status' => ['marital_status', 'marital status', 'status'],
            'state' => ['state', 'province'],
            'city' => ['city', 'town'],
            'location' => ['location', 'area', 'work location'],
            'current_salary' => ['current_salary', 'current salary', 'current ctc'],
            'preferred_salary' => ['preferred_salary', 'expected salary', 'expected_salary'],
            'languages' => ['languages', 'language', 'known languages'],
            'relocation' => ['relocation', 'relocate', 'willing to relocate']
        ];
        
        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header));
            
            foreach ($fieldMap as $field => $possibleNames) {
                foreach ($possibleNames as $name) {
                    if (strpos($headerLower, strtolower($name)) !== false) {
                        $mapping[$field] = $index;
                        break 2;
                    }
                }
            }
        }
        
        // Ensure required fields have mapping
        $requiredFields = ['name', 'email', 'mobile'];
        foreach ($requiredFields as $field) {
            if (!isset($mapping[$field])) {
                // Try to guess by position if not found by name
                if ($field === 'name' && isset($headers[0])) $mapping[$field] = 0;
                elseif ($field === 'email' && isset($headers[1])) $mapping[$field] = 1;
                elseif ($field === 'mobile' && isset($headers[2])) $mapping[$field] = 2;
            }
        }
        
        return $mapping;
    }
    
    /**
     * Get value from row using mapped indices
     */
    private function getValue(array $row, $field)
    {
        if (!isset($this->headerMapping[$field])) {
            return null;
        }
        
        $index = $this->headerMapping[$field];
        $values = array_values($row); // Re-index to numeric
        
        return $values[$index] ?? null;
    }

    public function model(array $row)
    {
        $this->rowNumber++;

        // Get values using mapped headers
        $name = trim((string) ($this->getValue($row, 'name') ?? ''));
        $email = trim((string) ($this->getValue($row, 'email') ?? ''));
        $mobile = trim((string) ($this->getValue($row, 'mobile') ?? ''));
        
        // Log for debugging
        \Log::info("Row {$this->rowNumber} data:", [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'mapping' => $this->headerMapping
        ]);
        
        // Skip if required fields are missing
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        if (empty($mobile)) {
            $errors[] = "Mobile is required";
        } elseif (!preg_match('/^[0-9\s\-\+\(\)]{7,15}$/', $mobile)) {
            // Extract only digits to check length
            $digitsOnly = preg_replace('/[^0-9]/', '', $mobile);
            if (strlen($digitsOnly) < 7 || strlen($digitsOnly) > 15) {
                $errors[] = "Mobile must be 7-15 digits";
            }
        }

        if (!empty($errors)) {
            $errorMsg = "Row {$this->rowNumber}: " . implode(', ', $errors);
            $this->errorMessages[] = $errorMsg;
            $this->failureCount++;
            \Log::error($errorMsg);
            
            $this->failures[] = new Failure(
                $this->rowNumber,
                'Row',
                $errors,
                $row
            );
            return null;
        }
        
        // Calculate age from DOB
        $age = null;
        $dob = null;
        $dobValue = $this->getValue($row, 'dob');
        if (!empty($dobValue)) {
            try {
                $dobDate = Carbon::parse($dobValue);
                $age = $dobDate->age;
                $dob = $dobDate->format('Y-m-d');
            } catch (\Exception $e) {
                $dob = null;
            }
        }
        
        // Handle languages
        $languages = $this->getValue($row, 'languages');
        if (is_string($languages)) {
            $languages = array_filter(array_map('trim', explode(',', $languages)));
        } else {
            $languages = [];
        }
        
        // Handle relocation
        $relocation = $this->getValue($row, 'relocation');
        if (is_string($relocation)) {
            $relocation = array_filter(array_map('trim', explode(',', $relocation)));
        } else {
            $relocation = [];
        }
        
        // Check for duplicates (only check email if provided)
        $query = Mobilization::where('mobile', $mobile);
        
        if (!empty($email)) {
            $query = $query->orWhere('email', $email);
        }
        
        $existing = $query->first();
        
        if ($existing) {
            $errorMsg = "Row {$this->rowNumber}: Duplicate record - Mobile already exists";
            if (!empty($email) && $existing->email === $email) {
                $errorMsg = "Row {$this->rowNumber}: Duplicate record - Email already exists";
            }
            $this->errorMessages[] = $errorMsg;
            $this->failureCount++;
            \Log::warning($errorMsg);
            
            $this->failures[] = new Failure(
                $this->rowNumber,
                'Row',
                ['Duplicate record exists'],
                $row
            );
            return null;
        }

        $this->successCount++;
        
        return new Mobilization([
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'highest_qualification' => $this->getValue($row, 'highest_qualification'),
            'dob' => $dob,
            'age' => $age,
            'gender' => $this->getValue($row, 'gender'),
            'marital_status' => $this->getValue($row, 'marital_status'),
            'state' => $this->getValue($row, 'state'),
            'city' => $this->getValue($row, 'city'),
            'location' => $this->getValue($row, 'location'),
            'current_salary' => $this->cleanNumeric($this->getValue($row, 'current_salary')),
            'preferred_salary' => $this->cleanNumeric($this->getValue($row, 'preferred_salary')),
            'languages' => $languages,
            'relocation' => $relocation,
        ]);
    }
    
    private function cleanNumeric($value)
    {
        if (empty($value)) return null;
        $clean = preg_replace('/[^0-9.]/', '', $value);
        return is_numeric($clean) ? $clean : null;
    }
    
    public function rules(): array
    {
        return [
            '*.name' => 'required',
            '*.email' => 'nullable|email',
            '*.mobile' => 'required|digits_between:7,15',
        ];
    }
    
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failureCount++;
            $this->errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
        $this->failures = array_merge($this->failures, $failures);
    }
    
    public function failures()
    {
        return $this->failures;
    }
}