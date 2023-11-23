<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\SpkKriteria;

class RaporController extends Controller
{
    public function edit($nisn) // Remove the type hint
    {
        // Fetch related "rapor" records for the given student
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail(); 
        $raporData = $siswa->rapor;
        $spkKriteria = $siswa->spk_kriteria;
        // dd($raporData, $spkKriteria);   
    
        return view('rapor.edit', ['siswa' => $siswa, 'raporData' => $raporData, 'spkKriteria' => $spkKriteria]);
    }

    public function update(Request $request, $nisn)
    {
        $request->validate([
            'pelajaran.*' => 'required',
            'sem_1_nilai_p.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_1_nilai_k.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0.9]{1,2})?)$/'],
            'sem_2_nilai_p.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_2_nilai_k.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_3_nilai_p.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_3_nilai_k.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_4_nilai_p.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_4_nilai_k.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_5_nilai_p.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
            'sem_5_nilai_k.*' => ['nullable', 'regex:/^(100(\.0{1,2})?|[0-9]?[0-9](\.[0-9]{1,2})?)$/'],
        ]);

    
        // Get the Siswa and related Rapor data by $nisn
        $siswa = Siswa::where('nisn', $nisn)->first();
    
        if (!$siswa) {
            // Handle the case where the Siswa is not found (optional)
            return redirect()->route('rapor.edit', $nisn)->with('error', 'Siswa not found.');
        }
    
        $arrayCount = count($request->pelajaran);
        // Loop through the indices
        for ($index = 0; $index < $arrayCount; $index++) {
            $raporData = [
                'nisn' => $nisn,
                'pelajaran'     => $request->pelajaran[$index] ?? null,
                'sem_1_nilai_p' => $request->sem_1_nilai_p[$index] ?? null,
                'sem_1_nilai_k' => $request->sem_1_nilai_k[$index] ?? null,
                'sem_2_nilai_p' => $request->sem_2_nilai_p[$index] ?? null,
                'sem_2_nilai_k' => $request->sem_2_nilai_k[$index] ?? null,
                'sem_3_nilai_p' => $request->sem_3_nilai_p[$index] ?? null,
                'sem_3_nilai_k' => $request->sem_3_nilai_k[$index] ?? null,
                'sem_4_nilai_p' => $request->sem_4_nilai_p[$index] ?? null,
                'sem_4_nilai_k' => $request->sem_4_nilai_k[$index] ?? null,
                'sem_5_nilai_p' => $request->sem_5_nilai_p[$index] ?? null,
                'sem_5_nilai_k' => $request->sem_5_nilai_k[$index] ?? null,
            ];
    
            // Calculate rata-rata (average) for nilai_P
            $filledPNilaiCount = 0;
            $totalPNilai = 0;

            for ($i = 1; $i <= 5; $i++) {
                if (!empty($raporData["sem_" . $i . "_nilai_p"])) {
                    $totalPNilai += $raporData["sem_" . $i . "_nilai_p"];
                    $filledPNilaiCount++;
                }
            }

            if ($filledPNilaiCount >= 1) {
                // At least 1 fields are filled for P, calculate the average
                $averagePNilai = $totalPNilai / $filledPNilaiCount;
            } else {
                $averagePNilai = null; // Set to null if less than 1 are filled
            }

            // Calculate rata-rata (average) for nilai_k
            $filledKNilaiCount = 0;
            $totalKNilai = 0;

            for ($i = 1; $i <= 5; $i++) {
                if (!empty($raporData["sem_" . $i . "_nilai_k"])) {
                    $totalKNilai += $raporData["sem_" . $i . "_nilai_k"];
                    $filledKNilaiCount++;
                }
            }

            if ($filledKNilaiCount >= 1) {
                // At least 1 fields are filled for K, calculate the average
                $averageKNilai = $totalKNilai / $filledKNilaiCount;
            } else {
                $averageKNilai = null; // Set to null if less than 2 are filled
            }
                
        
            // Update the corresponding rapor data based on $nisn and $pelajaran
            Rapor::where('nisn', $nisn)
            ->where('pelajaran', $raporData['pelajaran'])
            ->update([
                'sem_1_nilai_p' => $raporData['sem_1_nilai_p'],
                'sem_1_nilai_k' => $raporData['sem_1_nilai_k'],
                'sem_2_nilai_p' => $raporData['sem_2_nilai_p'],
                'sem_2_nilai_k' => $raporData['sem_2_nilai_k'],
                'sem_3_nilai_p' => $raporData['sem_3_nilai_p'],
                'sem_3_nilai_k' => $raporData['sem_3_nilai_k'],
                'sem_4_nilai_p' => $raporData['sem_4_nilai_p'],
                'sem_4_nilai_k' => $raporData['sem_4_nilai_k'],
                'sem_5_nilai_p' => $raporData['sem_5_nilai_p'],
                'sem_5_nilai_k' => $raporData['sem_5_nilai_k'],
                'rata_nilai_p' => $averagePNilai,
                'rata_nilai_k' => $averageKNilai,
            ]);
        }

        // Calculate the average of all rata_nilai_p from all matapelajaran
        $allRataNilaiP = Rapor::where('nisn', $nisn)->pluck('rata_nilai_p')->toArray();
        
        $rataRapor = (array_sum($allRataNilaiP)/count($allRataNilaiP));
        $rataRapor = number_format($rataRapor, 2);
        SpkKriteria::updateOrCreate(
            ['nisn' => $nisn],
            ['rapor' => $rataRapor]
        );
    
        // Optionally, you can redirect to a success page or show a success message
        return redirect()->route('rapor.edit', $nisn)->with('success', 'Rapor data has been updated successfully.');
    }
    
}
