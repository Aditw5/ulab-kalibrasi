<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Expertise</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body>

    @foreach ($raw as $raws)
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
                <div
                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8">
                    <div class="flex justify-center">
                        <img src="{{ asset('img/logo-rs.png') }}" width="50px" border="0">
                    </div>
                    <h1 class="text-gray-900 dark:text-white text-2xl md:text-5xl font-extrabold mb-2 text-center">RUMAH
                        SAKIT DAERAH GUNUNG JATI</h1>
                    <hr>
                    <h1 class="text-gray-900 dark:text-white text-lg md:text-5xl font-extrabold mb-2 text-center">
                        INSTALASI RADIOLOGI</h1>
                    <p class="text-lg font-normal text-gray-500 dark:text-gray-400 text-center">JL. Kesambi No. 56 Kota
                        Cirebon 45134 Telp. 0231 - 205330, Ext 1072</p>
                </div>


                <div
                    class="grid mb-8 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 md:mb-12 md:grid-cols-2 bg-white dark:bg-gray-800">
                    <span
                        class="bg-purple-100 text-purple-800 font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                        </svg>
                        Data Pasien
                    </span>
                    <figure
                        class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $raws->namapasien }} -
                                {{ $raws->nocm }} ({{ $raws->jeniskelamin }})</h3>
                            <p class="mt-4">TGL Lahir :
                                {{ \Carbon\Carbon::parse($raws->tgllahir)->translatedFormat('d F Y') }}</p>
                        </blockquote>
                    </figure>
                </div>
                <div
                    class="grid mb-8 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 md:mb-12 md:grid-cols-2 bg-white dark:bg-gray-800">
                    <span
                        class="bg-purple-100 text-purple-800 font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                        </svg>
                        Data Order
                    </span>
                    <figure
                        class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                        <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                            <p class="mt-4">Dokter Pengorder</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $raws->perujuk ?? '' }}
                            </h3>
                            <p class="mt-4">Ruangan Pengorder</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $raws->namaruangan ?? '' }}</h3>
                            <p class="mt-4">Dokter Radiologi</p>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $raws->dokterrad ?? '' }}
                            </h3>
                            <p class="mt-4">No. Pemeriksaan : {{ $raws->noregistrasi }}</p>
                            <p class="mt-1">TGL Pemeriksaan :
                                {{ \Carbon\Carbon::parse($raws->tglverif)->translatedFormat('d F Y') }}</p>
                        </blockquote>
                    </figure>
                </div>

                <div
                    class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                    <a href="#"
                        class="bg-purple-100 text-purple-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                        </svg>
                        Data Expertise
                    </a>
                    <h2 class="text-gray-900 dark:text-white text-xl font-extrabold mb-2">
                        {{ (string) $raws->namaproduk }}</h2>
                    <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">No Photo: {{ $raws->nofoto }}
                    </p>
                    <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">Klinis:
                        {{ $raws->catatanklinis }}</p>
                    @php
                        $exper = explode("\n", $raws->keterangan);
                    @endphp
                    @foreach ($exper as $index => $row)
                        <p class="text-lg font-normal">
                            @if ($row !== '')
                                {{ $row }}
                            @else
                                <div style="height: 20px;"></div> <!-- Ganti 20px dengan tinggi yang diinginkan -->
                            @endif
                        </p>
                    @endforeach
                    <p class="text-lg font-normal mt-4">Cirebon,
                        {{ \Carbon\Carbon::parse($raws->tglexpertise)->translatedFormat('d F Y') }}</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{ $raws->dokterrad }}">
                    <span style="font-size: 9pt"><b>{{ '( ' . $raws->dokterrad . ' )' }}
                        </b><br><i>{{ $raws->dokterradnosip != null ? $raws->dokterradnosip : '' }} </i></span>
                </div>
            </div>
            </div>
        </section>
    @endforeach
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>

</html>
