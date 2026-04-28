<?php

namespace App\Imports;

use App\Models\Mobilization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;

class MobilizationImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected $rowNumber = 0;
    public $successCount = 0;
    public $failureCount = 0;
    public $errorMessages = [];

    public function model(array $row)
    {
        $this->rowNumber++;

        // Debug: Log the row data
        \Log::info("Import row {$this->rowNumber} data:", $row);

        // Clean the data
        $row = array_map(function($value) {
            return is_string($value) ? trim($value) : $value;
        }, $row);

        // Validate required fields
        $name = $row['name'] ?? null;
        $email = $row['email'] ?? null;
        $mobile = $row['mobile'] ?? null;

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
            return null;
        }

        // Calculate age from DOB if provided
        $age = null;
        $dob = null;
        if (isset($row['dob']) && !empty($row['dob'])) {
            try {
                $dob = Carbon::parse($row['dob']);
                $age = $dob->age;
                $dob = $dob->format('Y-m-d');
            } catch (\Exception $e) {
                $age = null;
                $dob = null;
            }
        }

        $this->successCount++;

        return new Mobilization([
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'highest_qualification' => $row['highest_qualification'] ?? $row['qualification'] ?? null,
            'dob' => $dob,
            'age' => $age,
            'gender' => $row['gender'] ?? null,
            'marital_status' => $row['marital_status'] ?? null,
            'state' => $row['state'] ?? null,
            'city' => $row['city'] ?? null,
            'location' => $row['location'] ?? null,
            'current_salary' => is_numeric($row['current_salary'] ?? null) ? $row['current_salary'] : null,
            'preferred_salary' => is_numeric($row['preferred_salary'] ?? null) ? $row['preferred_salary'] : null,
            
            // Handle JSON fields
            'languages' => isset($row['languages']) ? array_filter(array_map('trim', explode(',', $row['languages']))) : [],
            'relocation' => isset($row['relocation']) ? array_filter(array_map('trim', explode(',', $row['relocation']))) : [],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'nullable|email',
            'mobile' => 'required|digits_between:7,15',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'mobile.required' => 'Mobile number is required',
        ];
    }
}