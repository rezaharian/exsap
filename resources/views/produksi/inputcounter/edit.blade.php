@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <style>
        .alert {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
            /* Transisi smooth */
        }

        .alert.fade-out {
            opacity: 0;
        }

        .is-invalid {
            border-color: #dc3545;
        }
    </style>


    <div class="row">

        {{-- <div class="col-md-2">
            <marquee behavior="" direction="">Jika ada kendala Dalam Pengisian Mohon Hubungi IT (Shift I), Terimakasih
            </marquee>
        </div> --}}
    </div>
    <div class="card p-1 pb-5 item-center">
        @if (session('success'))
            <div class="alert alert-primary">
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
        <h4 class="text-center my-3">Edit Input Counter {{ $jenis }}</h4>


        <form id="myForm" onsubmit="return validateFormWaktuKeterangan();"
            action="{{ route('produksi.inputcounter.update', [
                $data[0]['tanggal'],
                str_replace('/', '_', $data[0]['spk_nomor']), // Mengganti '/' dengan '_'
                $data[0]['line'],
                $data[0]['shift'],
                $data[0]['no_reg'],
                $jenis,
            ]) }}"
            method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="jenis" class="form-label m-0">Jenis: </label>
                        <input type="text" class="form-control form-control-sm m-0" id="jenis" name="jenis"
                            value="{{ $jenis }}" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label m-0">Tanggal : </label>
                        <input type="date" class="form-control form-control-sm m-0" id="tanggal" name="tanggal"
                            value="{{ \Carbon\Carbon::parse($data[0]['tanggal'])->format('Y-m-d') }}" readonly>

                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="spk_nomor" class="form-label m-0">SPK Nomor : </label>
                        <input type="text" class="form-control form-control-sm m-0" id="spk_nomor" name="spk_nomor"
                            value="{{ $data[0]['spk_nomor'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="line" class="form-label m-0">Line : </label>
                        <input type="text" class="form-control form-control-sm m-0" id="line" name="line"
                            value="{{ $data[0]['line'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="shift" class="form-label m-0">Shift : </label>
                        <select class="form-control form-control-sm m-0" id="shift" name="shift" readonly>
                            <option value="" disabled>Pilih Shift</option>
                            <option value="I" {{ $data[0]['shift'] == 'I' ? 'selected' : '' }}>I</option>
                            <option value="II" {{ $data[0]['shift'] == 'II' ? 'selected' : '' }}>II</option>
                            <option value="III" {{ $data[0]['shift'] == 'III' ? 'selected' : '' }}>III</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="no_reg" class="form-label m-0">NoReg : </label>
                        <input type="text" class="form-control form-control-sm m-0" id="no_reg" name="no_reg"
                            value="{{ $data[0]['no_reg'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="leader" class="form-label m-0">Leader : </label>
                        <input type="text" class="form-control form-control-sm m-0" id="leader" name="leader"
                            value="{{ $data[0]['leader'] }}" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label for="spv" class="form-label m-0">SPV : </label>
                        <input type="text" class="form-control form-control-sm m-0" id="spv" name="spv"
                            value="{{ $data[0]['spv'] }}" readonly>
                    </div>
                </div>
            </div>

            <div class="table-responsive text-secondary">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr class="text-center">
                            <th hidden>#</th>
                            <th>Jam</th>
                            <th>Reset Count</th>
                            <th>Akhir</th>
                            <th>Hasil</th>
                            <th>Reset Time</th>
                            <th>Jam Akhir</th>
                            <th>Status SPK</th>
                            <th>Keterangan</th>
                            <th>Waktu</th>
                            <th>Setting</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td hidden>
                                    <input type="text" class="form-control form-control-sm m-0"
                                        name="data[{{ $key }}][id]" value="{{ $item['id'] }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm m-0 text-center"
                                        name="data[{{ $key }}][jam]" value="{{ $item['jam'] }}" readonly>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm m-0"
                                        name="data[{{ $key }}][reset_count]">
                                        <option value="Ya" {{ $item['reset_count'] == 'Ya' ? 'selected' : '' }}>Ya
                                        </option>
                                        <option value="Tidak" {{ $item['reset_count'] == 'Tidak' ? 'selected' : '' }}>
                                            Tidak</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 akhir"
                                        name="data[{{ $key }}][akhir]" value="{{ $item['akhir'] }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 hasil"
                                        name="data[{{ $key }}][hasil]" value="{{ $item['hasil'] }}" readonly>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm m-0"
                                        name="data[{{ $key }}][reset_time]"
                                        onchange="handleResetTimeChange(this, '{{ $key }}')">
                                        <option value="Ya" {{ $item['reset_time'] == 'Ya' ? 'selected' : '' }}>Ya
                                        </option>
                                        <option value="Tidak" {{ $item['reset_time'] == 'Tidak' ? 'selected' : '' }}>
                                            Tidak
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text" class="form-control form-control-sm m-0"
                                        value="{{ $item['jam_akhir'] }}" name="data[{{ $key }}][jam_akhir]"
                                        placeholder="MM:DD" maxlength="5" oninput="ft(this)"
                                        onkeydown="preventColonDelete(event)">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm m-0"
                                        name="data[{{ $key }}][stat_spk]">
                                        <option value="produksi" {{ $item['stat_spk'] == '' ? 'selected' : '' }}>
                                        </option>

                                        <option value="mulai" {{ $item['stat_spk'] == 'mulai' ? 'selected' : '' }}>Mulai
                                        </option>
                                        <option value="selesai" {{ $item['stat_spk'] == 'selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm m-0 keterangan-input"
                                        name="data[{{ $key }}][keterangan]" value="{{ $item['Keterangan'] }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm m-0 waktu-input"
                                        placeholder="Menit" name="data[{{ $key }}][waktu]"
                                        value="{{ $item['waktu'] }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check m-0">
                                            <!-- Hidden input agar tetap kirim setting = "" jika tidak dicentang -->
                                            <input type="hidden" name="data[{{ $key }}][setting]"
                                                value="">

                                            <!-- Checkbox -->
                                            <input type="checkbox" class="form-check-input setting-checkbox"
                                                id="setting_{{ $key }}"
                                                name="data[{{ $key }}][setting]" value="ya"
                                                {{ isset($item['setting']) && $item['setting'] === 'ya' ? 'checked' : '' }}>

                                            <label class="form-check-label" for="setting_{{ $key }}"></label>
                                        </div>

                                        <div class="jumlah-setting-wrapper"
                                            style="width: 100px; {{ isset($item['setting']) && $item['setting'] == 'ya' ? 'display: block;' : 'display: none;' }}">
                                            <input type="number"
                                                class="form-control form-control-sm jumlah-setting-input"
                                                name="data[{{ $key }}][jumlah_waste_setting]"
                                                value="{{ $item['jumlah_waste_setting'] ?? '' }}"
                                                placeholder="PCS Waste">
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-start mt-2">
                <h5 class="font-weight-bold">Total Hasil:</h5>
                <h2 class="text-primary" id="totalHasil">0</h2>
            </div>


            <div class="text-center">
                <button type="submit" class="btn btn-primary ">Update</button>
            </div>
        </form>
        {{-- buat validasi waktu harus di isi --}}
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
        //FUNGSI MEMBUAT TOTAL
        document.addEventListener('DOMContentLoaded', function() {
            const akhirInputs = document.querySelectorAll('.akhir');
            const hasilInputs = document.querySelectorAll('.hasil');
            const totalHasilElement = document.getElementById('totalHasil');

            function updateResults() {
                let totalHasil = 0; // Inisialisasi total hasil
                akhirInputs.forEach((input, index) => {
                    const currentAkhir = parseFloat(input.value) || 0;

                    if (currentAkhir === 0) {
                        hasilInputs[index].value = 0;
                    } else if (index > 0) {
                        const previousAkhir = parseFloat(akhirInputs[index - 1].value) || 0;
                        hasilInputs[index].value = currentAkhir - previousAkhir;
                    } else {
                        hasilInputs[index].value = currentAkhir;
                    }

                    // Tambahkan hasil ke total
                    totalHasil += parseFloat(hasilInputs[index].value) || 0;
                });

                // Update elemen total hasil
                totalHasilElement.textContent = totalHasil;
            }

            akhirInputs.forEach(input => {
                input.addEventListener('input', updateResults);
            });

            // Panggil fungsi updateResults saat halaman dimuat untuk menampilkan total awal
            updateResults();
        });

        //mereset time
        function handleResetTimeChange(selectElement, key) {
            const jamAkhirInput = document.querySelector(`input[name="data[${key}][jam_akhir]"]`);

            if (selectElement.value === 'Ya') {
                jamAkhirInput.value = '00:00'; // Set jam_akhir to 00:00
            }
        }

        //fungsi untuk mengaur format jam max 99:99 
        function ft(input) {
            let value = input.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka

            // Batasi input hanya 4 digit (HHMM)
            if (value.length > 4) {
                value = value.slice(0, 4);
            }

            // Jika lebih dari 2 digit, bagi menjadi jam dan menit
            let hours = value.slice(0, 2);
            let minutes = value.slice(2, 4);

            // Batasi jam antara 00 dan 23
            if (hours > 23) {
                hours = '23';
            }

            // Batasi menit antara 00 dan 59
            if (minutes > 59) {
                minutes = '59';
            }

            // Gabungkan kembali dengan format HH:MM
            if (value.length > 2) {
                value = hours + ':' + minutes;
            } else {
                value = hours;
            }

            // Atur kembali nilai ke input field
            input.value = value;
        }





        //membuat hasil otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const akhirInputs = document.querySelectorAll('.akhir');
            const hasilInputs = document.querySelectorAll('.hasil');
            const resetCountSelects = document.querySelectorAll('select[name*="[reset_count]"]');

            function updateResults() {
                akhirInputs.forEach((input, index) => {
                    const currentAkhir = parseFloat(input.value) || 0;
                    const resetCount = resetCountSelects[index].value; // Get reset_count value

                    if (resetCount === 'Ya') {
                        // Jika reset_count adalah 'Ya', maka hasil sama dengan akhir
                        hasilInputs[index].value = currentAkhir;
                    } else {
                        // Menggunakan rumus hanya jika reset_count adalah 'Tidak'
                        if (currentAkhir === 0) {
                            hasilInputs[index].value = 0;
                        } else if (index > 0) {
                            const previousAkhir = parseFloat(akhirInputs[index - 1].value) || 0;
                            hasilInputs[index].value = currentAkhir - previousAkhir;
                        } else {
                            hasilInputs[index].value = currentAkhir;
                        }
                    }
                });
            }

            // Update results when 'akhir' input changes
            akhirInputs.forEach(input => {
                input.addEventListener('input', function() {
                    updateResults();
                });
            });

            // Update results when 'reset_count' select changes
            resetCountSelects.forEach(select => {
                select.addEventListener('change', function() {
                    updateResults();
                });
            });

            // Trigger update results on load to reflect initial state
            updateResults();
        });



        //auto update
        // Mengatur waktu tidak aktif dalam milidetik (1 jam = 3600000 ms)
        var inactiveTimeLimit = 3600000;
        var inactivityTimer;

        function resetInactivityTimer() {
            // Reset timer jika ada aktivitas (klik, ketikan, gerakan mouse)
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(autoSubmit, inactiveTimeLimit);
        }

        function autoSubmit() {
            // Aksi ketika tidak ada aktivitas selama 1 jam
            document.querySelector('form').submit(); // Melakukan submit form secara otomatis
        }



        // Mendengarkan event yang dianggap aktivitas
        window.onload = resetInactivityTimer; // Set timer saat halaman dimuat
        window.onmousemove = resetInactivityTimer; // Reset saat mouse digerakkan
        window.onkeypress = resetInactivityTimer; // Reset saat ada input keyboard
        window.onclick = resetInactivityTimer; // Reset saat ada klik

        //membuat mouse gerak dikit, agar layar tidka mati
        let keepAwakeInterval;
        let idleTimeout;

        // Fungsi untuk menjaga layar tetap aktif
        function keepScreenAwake() {
            const dummyElement = document.createElement('div');
            dummyElement.style.position = 'absolute';
            dummyElement.style.top = '0';
            dummyElement.style.left = '0';
            dummyElement.style.width = '1px';
            dummyElement.style.height = '1px';
            dummyElement.style.opacity = '0';
            document.body.appendChild(dummyElement);

            console.log('Fungsi menjaga layar tetap aktif dimulai.');

            // Gerakkan elemen dummy setiap 60 detik untuk mencegah sleep
            keepAwakeInterval = setInterval(() => {
                dummyElement.style.transform = `translateY(${Math.random() * 10}px)`;
                console.log('Elemen dummy bergerak untuk mencegah layar tidur.');
            }, 60000); // Setiap 60 detik
        }

        // Fungsi untuk menghentikan menjaga layar tetap aktif
        function stopKeepScreenAwake() {
            clearInterval(keepAwakeInterval);
            console.log('Fungsi menjaga layar dihentikan.');
        }

        // Fungsi yang berjalan setelah 30 detik tanpa aktivitas pengguna
        function onUserIdle() {
            console.log('Tidak ada aktivitas selama 30 detik. Menjaga layar tetap aktif...');
            keepScreenAwake();
        }

        // Reset timer setiap kali ada aktivitas (mouse, keyboard, sentuhan)
        function resetIdleTimer() {
            clearTimeout(idleTimeout);
            stopKeepScreenAwake(); // Hentikan fungsi jika ada aktivitas

            // Mulai hitung waktu 30 detik setelah aktivitas terakhir
            idleTimeout = setTimeout(onUserIdle, 30000); // 30 detik tanpa aktivitas
        }

        // Pasang event listener untuk memantau aktivitas pengguna
        document.addEventListener('mousemove', resetIdleTimer);
        document.addEventListener('keypress', resetIdleTimer);
        document.addEventListener('touchstart', resetIdleTimer);

        // Jalankan resetIdleTimer ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Halaman dimuat. Menunggu aktivitas pengguna...');
            resetIdleTimer();
        });

        // Pilihan: Hentikan saat halaman ditutup
        window.addEventListener('beforeunload', () => {
            stopKeepScreenAwake();
            console.log('Halaman ditutup, fungsi menjaga layar dihentikan.');
        });


        // Fungsi untuk menghilangkan alert setelah 5 detik
        setTimeout(function() {
            // Ambil semua elemen dengan class 'alert'
            var alerts = document.querySelectorAll('.alert');

            // Tambahkan class 'fade-out' untuk membuat transisi opacity
            alerts.forEach(function(alert) {
                alert.classList.add('fade-out');
            });

            // Hapus elemen setelah transisi selesai (0.5s + delay 5s)
            setTimeout(function() {
                alerts.forEach(function(alert) {
                    alert.remove();
                });
            }, 500); // Waktu sesuai durasi transisi di CSS (0.5 detik)

        }, 5000); // 5 detik sebelum alert menghilang

        // dari sini untuk tambahan setting

        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.setting-checkbox');
            const form = document.getElementById("myForm");

            checkboxes.forEach(function(checkbox) {
                // Jalankan saat halaman pertama kali load (untuk kondisi edit data)
                toggleJumlahSettingInput(checkbox);

                // Jalankan saat user ubah checkbox
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
                    event.preventDefault(); // Cegah kirim
                    alert("Mohon isi jumlah waste setting untuk checkbox yang dicentang.");
                }
            });
        });
    </script>



@endsection
