<?php

namespace App\Imports;

use App\Models\Mobilization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;

class FlexibleMobilizationImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected $headerMapping = [];
    protected $remark;
    protected $rowNumber = 0;
    public $successCount = 0;
    public $failureCount = 0;
    public $errorMessages = [];

    public function __construct($remark = null)
    {
        $this->remark = $remark;
        $this->headerMapping = $this->getHeaderMapping();
        $this->successCount = 0;
        $this->failureCount = 0;
        $this->errorMessages = [];
    }

    /**
     * Map possible header names
     */
    private function getHeaderMapping()
    {
        return [
            'name' => ['name', 'full_name', 'candidate name', 'candidate_name', 'fullname'],
            'email' => ['email', 'email address', 'email_address', 'email id'],
            'mobile' => ['mobile', 'phone', 'contact', 'phone number', 'mobile number'],
            'highest_qualification' => ['highest_qualification', 'qualification', 'education'],
            'dob' => ['dob', 'date of birth', 'date_of_birth', 'birth date'],
            'gender' => ['gender', 'sex'],
            'marital_status' => ['marital_status', 'marital status'],
            'state' => ['state', 'province'],
            'city' => ['city', 'district', 'town'],
            'location' => ['location', 'address', 'area'],
            'current_salary' => ['current_salary', 'current salary', 'current ctc'],
            'preferred_salary' => ['preferred_salary', 'expected salary', 'expected ctc'],
            'languages' => ['languages', 'language', 'language known'],
            'relocation' => ['relocation', 'relocation preference']
        ];
    }

    private function findColumn(array $row, $field)
    {
        $possibleNames = $this->headerMapping[$field] ?? [$field];

        foreach ($possibleNames as $name) {
            foreach (array_keys($row) as $columnName) {
                if (strtolower(trim($columnName)) === strtolower(trim($name))) {
                    return $columnName;
                }
            }
        }

        return null;
    }

    private function getValue(array $row, $field, $default = null)
    {
        $column = $this->findColumn($row, $field);
        return $column ? ($row[$column] ?? $default) : $default;
    }

    public function model(array $row)
    {
        $this->rowNumber++;
        
        $name = trim((string) ($this->getValue($row, 'name') ?? ''));
        $email = trim((string) ($this->getValue($row, 'email') ?? ''));
        $mobile = trim((string) ($this->getValue($row, 'mobile') ?? ''));

        // fallback if column names unknown
        if (empty($name) || empty($email) || empty($mobile)) {
            $values = array_values(array_filter($row, function($val) {
                return !is_null($val) && $val !== '';
            }));
            if (empty($name) && isset($values[0])) $name = trim((string) $values[0]);
            if (empty($email) && isset($values[1])) $email = trim((string) $values[1]);
            if (empty($mobile) && isset($values[2])) $mobile = trim((string) $values[2]);
        }

        // Skip completely empty rows
        if (empty($name) && empty($email) && empty($mobile)) {
            \Log::info("Row {$this->rowNumber}: Skipped - empty row");
            return null;
        }

        // Validate required fields
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
            return null;
        }

        // Check for duplicates in database (only check email if provided)
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
            return null;
        }

        // calculate age
        $age = null;
        $dob = $this->getValue($row, 'dob');

        if (!empty($dob)) {
            try {
                $dob = Carbon::parse($dob);
                $age = $dob->age;
            } catch (\Exception $e) {
                $dob = null;
                $age = null;
            }
        }

        // languages
        $languages = $this->getValue($row, 'languages', '');
        if (is_string($languages)) {
            $languages = array_map('trim', explode(',', $languages));
            $languages = array_filter($languages); // Remove empty values
        } elseif (!$languages) {
            $languages = [];
        }

        // relocation
        $relocation = $this->getValue($row, 'relocation', '');
        if (is_string($relocation)) {
            $relocation = array_map('trim', explode(',', $relocation));
            $relocation = array_filter($relocation); // Remove empty values
        } elseif (!$relocation) {
            $relocation = [];
        }

        $this->successCount++;
        \Log::info("Row {$this->rowNumber}: Successfully imported - {$name} ({$email})");

        return new Mobilization([
            'identification_remark' => $this->remark,
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
            'current_salary' => $this->getNumericValue($this->getValue($row, 'current_salary')),
            'preferred_salary' => $this->getNumericValue($this->getValue($row, 'preferred_salary')),
            'languages' => $languages,
            'relocation' => $relocation,
        ]);
    }

    private function getNumericValue($value)
    {
        if (empty($value)) return null;

        $value = preg_replace('/[^0-9.-]/', '', $value);

        return is_numeric($value) ? $value : null;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.email' => 'nullable|email',
            '*.mobile' => 'required|digits_between:7,15',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Name is required in row',
            '*.email.required' => 'Email is required in row',
            '*.email.email' => 'Invalid email format in row',
            '*.mobile.required' => 'Mobile is required in row',
        ];
    }
}