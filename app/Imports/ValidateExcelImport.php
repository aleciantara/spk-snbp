<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ValidateExcelImport implements WithHeadingRow, WithStartRow, OnEachRow
{
    use SkipsFailures, SkipsErrors;

    public function startRow(): int
    {
        return 5; // Specify the starting row for data extraction
    }

    private $peminatanApa;
    private $indexPkn;
    private $headers = [];
    private $headerProcessed = false; // Flag to track if headers have been processed

    private $expectedIPSHeaders = [
        'PKN'     => ['PKN', 'Pendidikan Pancasila dan Kewarganegaraan', 'Civic'],
        'BINDO'   => ['BINDO', 'Bahasa Indonesia' ],
        'MAT'     => ['MAT', 'Matematika'],
        'SEJINDO' => ['SEJINDO', 'Sejarah Indonesia'],
        'BING'    => ['BING', 'Bahasa Inggris'],
        'SENI'    => ['SENI', 'Seni Budaya'],
        'PKWU'    => ['PKWU', 'Prakarya dan Kewirausahaan'],
        'PENJAS'  => ['PENJAS', 'Pendidikan Jasmani Olahraga dan Kesehatan', 'PJOK'],
        'GEO'     => ['GEO', 'Geografi'],
        'SEJ'     => ['SEJ', 'Sejarah (minat)', 'Sejarah'],
        'SOS'     => ['SOS', 'Sosiologi'],
        'EKO'     => ['EKO', 'Ekonomi']
    ];
    
    private $expectedMIPAHeaders = [
        'PKN'     => ['PKN', 'Pendidikan Pancasila dan Kewarganegaraan', 'Civic'],
        'BINDO'   => ['BINDO', 'Bahasa Indonesia'],
        'MAT'     => ['MAT', 'Matematika'],
        'SEJINDO' => ['SEJINDO', 'Sejarah Indonesia'],
        'BING'    => ['BING', 'Bahasa Inggris'],
        'SENI'    => ['SENI', 'Seni Budaya'],
        'PKWU'    => ['PKWU', 'Prakarya dan Kewirausahaan'],
        'PENJAS'  => ['PENJAS', 'Pendidikan Jasmani Olahraga dan Kesehatan', 'PJOK'],
        'MATMIN'  => ['MATMIN', 'Matematika (Minat)', 'Matematika Minat', 'MATMIN / GEO'],
        'BIO'     => ['BIO', 'Biologi'],
        'FIS'     => ['FIS', 'Fisika'],
        'KIM'     => ['KIM', 'Kimia']
    ];

    public function __construct($indexPkn, $peminatanApa)
    {
        $this->peminatanApa = $peminatanApa;
        $this->indexPkn = $indexPkn;
    }

    // Method to process each row
    public function onRow(Row $row)
    {
        // Get the row index
        $rowIndex = $row->getIndex();
        
        // Check if headers have already been processed
        if ($this->headerProcessed) {
            return; // Skip further processing
        }

        // Capture headers on the first data row (assumed to be the 5th row)
        if ($rowIndex === 5) {
            $rowData = $row->toArray(); // Convert row to an array
            $slicedRowData = array_slice($rowData, $this->indexPkn, 12);
            $actualHeaders = array_values($slicedRowData);

            $this->headers = $actualHeaders; // Store the headers

            // Get the expected headers based on peminatan
            $expectedHeaders = $this->peminatanApa === 'IPS' 
                ? array_keys($this->expectedIPSHeaders)
                : array_keys($this->expectedMIPAHeaders);
            
            // Validate the headers
            $this->validateHeaders($this->headers, $expectedHeaders);
            
            // Set the flag to true after processing row 5
            $this->headerProcessed = true;

            return; // Exit the function after processing row 5
        }

        // If you want to do something with other rows, you can handle it here.
    }

    private function validateHeaders(array $actualHeaders, array $expectedHeaders)
    {
        // Check if the number of headers match
        if (count($actualHeaders) < count($expectedHeaders)) {
            throw new \Exception('Jumlah kolom tidak sesuai dengan struktur standar.');
        }

        // Compare each header
        foreach ($expectedHeaders as $index => $expectedHeader) {
            // Ensure the index exists in actualHeaders
            if (isset($actualHeaders[$index])) {
                $headerToCheck = $actualHeaders[$index];
                // Check if the header matches any of the expected values (primary or alternate)
                $expectedValues = $this->expectedIPSHeaders[$expectedHeader] ?? $this->expectedMIPAHeaders[$expectedHeader] ?? [];

                if (!in_array($headerToCheck, $expectedValues)) {
                    throw new \Exception("Kolom header tidak sesuai pada posisi ke-" . ($index + 1) . ". Diharapkan: $expectedHeader, Ditemukan: " . $headerToCheck);
                }
            } else {
                // Handle the case where the index doesn't exist in actualHeaders
                throw new \Exception("Kolom header tidak ditemukan pada posisi ke-" . ($index + 1) . ". Diharapkan: $expectedHeader.");
            }
        }
    }



    // Method to get headers after import validation
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
