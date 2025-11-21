@extends('layouts.app')

@section('content')
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-1 pb-5 item-center">
        <h4 class="text-center my-3">Create Input Counter {{ $jenis }}</h4>
        <form id="myForm" onsubmit="return validateFormWaktuKeterangan();"
            action="{{ route('produksi.inputcounter.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="jenis" class="form-label m-0">Jenis : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="jenis" name="jenis"
                            value="{{ $jenis }}" readonly>
                    </div>
                </div>
                <div class="col-md-2">

                    <div class="mb-3">
                        <label for="tanggal" class="form-label m-0">Tanggal : </label>
                        <input type="date" class="form-control form-control-sm m-0 " id="tanggal" name="tanggal"
                            value="{{ $tanggal }}" readonly>
                    </div>
                </div>
                <div class="col-md-2">

                    <div class="mb-3">
                        <label for="spk_nomor" class="form-label m-0">SPK Nomor : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="spk_nomor" name="spk_nomor"
                            value="{{ $spk_nomor }}" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="line" class="form-label m-0">Line : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="line" name="line"
                            value="{{ $line }}" readonly>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="shift" class="form-label m-0">Shift : </label>
                        <select class="form-control form-control-sm m-0 " id="shift" name="shift" readonly>
                            <option value="" disabled selected>Pilih Shift</option>
                            <option value="I" {{ $shift == 'I' ? 'selected' : '' }}>I</option>
                            <option value="II" {{ $shift == 'II' ? 'selected' : '' }}>II</option>
                            <option value="III" {{ $shift == 'III' ? 'selected' : '' }}>III</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="no_reg" class="form-label m-0">NoReg : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="no_reg" name="no_reg"
                            value="{{ $no_reg }}" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="leader" class="form-label m-0">Leader : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="leader" name="leader"
                            value="{{ $leader }}" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="spv" class="form-label m-0">SPV : </label>
                        <input type="text" class="form-control form-control-sm m-0 " id="spv" name="spv"
                            value="{{ $spv }}" readonly>
                    </div>
                </div>

            </div>


            <div class="table-responsive text-secondary">
                <table class="table table-sm table-hover ">
                    <thead>
                        <tr class="text-center">
                            <th hidden>#</th>
                            <th>Jam</th>
                            <th>Reset Count</th>
                            <th>Akhir</th>
                            <th>Hasil</th>
                            <th>Reset Time</th>
                            <th>Jam Akhir</th>
                            <th>Keterangan</th>
                            <th>Waktu</th>
                            <th>Setting</th>

                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 8; $i++)
                            <tr>
                                <td hidden>
                                    <input type="text" class="form-control form-control-sm m-0"
                                        name="data[{{ $i }}][idf]"
                                        value="{{ now()->format('YmdHis') . ($i + 1) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm m-0 text-center"
                                        name="data[{{ $i }}][jam]" readonly>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm m-0"
                                        name="data[{{ $i }}][reset_count]">
                                        <option value="Ya" {{ $i == 0 ? 'selected' : '' }}>Ya</option>
                                        <option value="Tidak" {{ $i != 0 ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 akhir"
                                        name="data[{{ $i }}][akhir]">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 hasil"
                                        name="data[{{ $i }}][hasil]" readonly>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm m-0"
                                        name="data[{{ $i }}][reset_time]">
                                        <option value="Ya" {{ $i == 0 && $jenis != 'Extruder' ? 'selected' : '' }}>Ya
                                        </option>
                                        <option value="Tidak" {{ $jenis == 'Extruder' || $i != 0 ? 'selected' : '' }}>
                                            Tidak</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text" class="form-control form-control-sm m-0"
                                        name="data[{{ $i }}][jam_akhir]" placeholder="HH:MM"
                                        oninput="ft(this)" onkeydown="preventColonDelete(event)" maxlength="5">
                                    <!-- Set maxlength to 5 for HH:MM -->
                                </td>


                                <td>
                                    <input type="text" class="form-control form-control-sm m-0 keterangan-input"
                                        name="data[{{ $i }}][keterangan]">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 waktu-input"
                                        name="data[{{ $i }}][waktu]">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check m-0">
                                            <!-- Hidden input agar tetap kirim nilai kosong jika tidak dicentang -->
                                            <input type="hidden" name="data[{{ $i }}][setting]"
                                                value="">

                                            <!-- Checkbox -->
                                            <input type="checkbox" class="form-check-input setting-checkbox"
                                                id="setting_create_{{ $i }}"
                                                name="data[{{ $i }}][setting]" value="ya">

                                            <label class="form-check-label"
                                                for="setting_create_{{ $i }}"></label>
                                        </div>

                                        <!-- Input jumlah waste, disembunyikan sampai checkbox dicentang -->
                                        <div class="jumlah-setting-wrapper" style="width: 100px; display: none;">
                                            <input type="number"
                                                class="form-control form-control-sm jumlah-setting-input"
                                                name="data[{{ $i }}][jumlah_waste_setting]"
                                                placeholder="PCS Waste">
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>



            <div class="text-center">

                <button type="submit" class="btn btn-primary ">Submit</button>
            </div>
        </form>
        <script>
            function validateFormWaktuKeterangan() {
                let isValid = true;
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach((row) => {
                    const keteranganInput = row.querySelector('.keterangan-input');
                    const waktuInput = row.querySelector('.waktu-input');

                    const keterangan = keteranganInput.value.trim();
                    const waktu = waktuInput.value.trim();

                    // Reset error
                    keteranganInput.classList.remove('is-invalid');
                    waktuInput.classList.remove('is-invalid');

                    // Jika hanya salah satu yang diisi
                    if ((keterangan && !waktu) || (!keterangan && waktu)) {
                        isValid = false;
                        keteranganInput.classList.add('is-invalid');
                        waktuInput.classList.add('is-invalid');
                    }
                });

                if (!isValid) {
                    alert("Waktu  downtime harus di isi ketika ada keterangan downtime.");
                    return false; // Mencegah submit
                }

                return true; // Lolos validasi
            }
        </script>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shiftInput = document.getElementById('shift');
            const jamInputs = document.querySelectorAll('input[name^="data"][name$="[jam]"]');

            // Daftar jam berdasarkan shift
            const jamShift = {
                "I": ["07:00-08:00", "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
                    "12:00-13:00", "13:00-14:00", "14:00-15:00"
                ],
                "II": ["15:00-16:00", "16:00-17:00", "17:00-18:00", "18:00-19:00", "19:00-20:00",
                    "20:00-21:00",
                    "21:00-22:00", "22:00-23:00"
                ],
                "III": ["23:00-24:00", "00:00-01:00", "01:00-02:00", "02:00-03:00", "03:00-04:00",
                    "04:00-05:00",
                    "05:00-06:00", "06:00-07:00"
                ]
            };

            // Fungsi untuk mengisi jam secara otomatis
            function isiJamOtomatis(shift) {
                if (jamShift[shift]) {
                    jamInputs.forEach((input, index) => {
                        input.value = jamShift[shift][index] ||
                            ''; // Mengisi jam atau kosongkan jika index melebihi array
                    });
                } else {
                    jamInputs.forEach(input => input.value = ''); // Kosongkan jika shift tidak valid
                }
            }

            // Saat shift berubah, isi jam otomatis
            shiftInput.addEventListener('change', function() {
                const selectedShift = this.value;
                isiJamOtomatis(selectedShift);
            });

            // Jika halaman sudah memiliki nilai shift, isi jam saat pertama kali halaman dimuat
            const currentShift = shiftInput.value;
            if (currentShift) {
                isiJamOtomatis(currentShift);
            }
        });

        //membuat hasil otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const akhirInputs = document.querySelectorAll('.akhir');
            const hasilInputs = document.querySelectorAll('.hasil');

            function updateResultForRow(index) {
                const previousAkhir = index > 0 ? parseFloat(akhirInputs[index - 1].value) || 0 : 0;
                const currentAkhir = parseFloat(akhirInputs[index].value) || 0;
                hasilInputs[index].value = currentAkhir - previousAkhir;
            }

            akhirInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    updateResultForRow(index);
                });
            });
        });

        const jenis = @json($jenis); // Ambil data jenis dari server

        // Fungsi untuk mengatur format jam max 99:99 untuk "Extruder" dan 23:59 untuk "Printing"
        function ft(input) {
            let value = input.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
            let maxLength = 4; // Default panjang maksimal
            let maxHours = 99; // Default jam maksimal untuk Extruder

            if (jenis === "Printing") {
                maxLength = 5; // Jika jenis Printing, panjang maksimal adalah 5 (hh:mm)
                maxHours = 23; // Jam maksimal untuk Printing adalah 23
            }

            if (value.length > maxLength) {
                value = value.slice(0, maxLength); // Batasi input hanya sesuai panjang maksimal
            }

            // Tambahkan titik dua setelah dua digit jika panjangnya lebih dari 2
            if (value.length > 2) {
                const hours = parseInt(value.slice(0, 2), 10);
                const minutes = parseInt(value.slice(2), 10);

                // Jika jenis adalah Printing, pastikan jam tidak lebih dari 23 dan menit tidak lebih dari 59
                if (jenis === "Printing") {
                    if (hours > maxHours) {
                        value = '23'; // Atur jam ke maksimum jika lebih dari 23
                    }

                    // Batasi menit maksimal 59
                    if (minutes > 59) {
                        value = value.slice(0, 2) + '59'; // Atur menit ke 59
                    }
                } else {
                    // Untuk jenis Extruder, tidak ada batasan jam di atas 99
                    input.value = value.slice(0, 2) + ':' + value.slice(2);
                    return; // Keluar dari fungsi setelah penetapan format
                }
            }

            // Format output menjadi hh:mm
            if (value.length > 2) {
                input.value = value.slice(0, 2) + ':' + value.slice(2, 4); // Pastikan hanya dua digit untuk menit
            } else {
                input.value = value; // Jika kurang dari 2 digit, tampilkan saja
            }
        }

        // Fungsi untuk mencegah penghapusan titik dua
        function preventColonDelete(event) {
            const input = event.target;
            if (event.key === "Backspace" || event.key === "Delete") {
                if (input.selectionStart === 3) { // Mencegah penghapusan titik dua
                    event.preventDefault();
                }
            }
        }




        //untuk tambahan setting
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.setting-checkbox');
            const form = document.getElementById("myForm");

            checkboxes.forEach(function(checkbox) {
                toggleJumlahSettingInput(checkbox);

                checkbox.addEventListener('change', function() {
                    toggleJumlahSettingInput(this);
                });
            });

            function toggleJumlahSettingInput(checkbox) {
                const wrapper = checkbox.closest('td').querySelector('.jumlah-setting-wrapper');
                const input = wrapper.querySelector('input');

                if (checkbox.checked) {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                    input.value = '';
                    input.classList.remove('is-invalid');
                }
            }

            form.addEventListener("submit", function(event) {
                let isValid = true;

                checkboxes.forEach(function(checkbox) {
                    const wrapper = checkbox.closest('td').querySelector('.jumlah-setting-wrapper');
                    const input = wrapper.querySelector('input');

                    if (checkbox.checked && input.value.trim() === "") {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    alert("Mohon isi jumlah waste setting untuk checkbox yang dicentang.");
                }
            });
        });
    </script>
@endsection
