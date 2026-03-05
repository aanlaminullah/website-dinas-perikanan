@extends('layouts.app')

@section('title', 'Publikasi Data - Dinas Perikanan Bolmut')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <h2 class="text-3xl font-heading font-bold text-gray-900 border-l-4 border-fish-blue pl-4">
                    Publikasi Data Budidaya Perikanan
                </h2>
                <form method="GET" class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600">Tahun:</label>
                    <select name="tahun" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fish-blue">
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="mb-12">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <h3 class="font-bold text-lg text-gray-800">Tren Produksi Budidaya {{ $tahun }}</h3>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-600 whitespace-nowrap">Komoditas:</label>
                            <select id="filterKomoditas"
                                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-fish-blue">
                                <option value="semua">Semua Komoditas</option>
                                @foreach ($data->flatten()->pluck('komoditas')->unique()->sort() as $komoditas)
                                    <option value="{{ $komoditas }}">{{ $komoditas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="relative w-full" style="min-height:260px;">
                        <canvas id="produksiChart"></canvas>
                    </div>
                    <p class="text-xs text-gray-400 mt-3 italic">
                        * Data yang ditampilkan merupakan akumulasi dari laporan bulanan di 6 kecamatan
                        Kabupaten Bolmut.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800">Laporan Data Produksi Perikanan Budidaya</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border border-gray-300 px-2 py-2 text-center" rowspan="2">NO</th>
                                <th class="border border-gray-300 px-2 py-2 text-center" rowspan="2">Kode<br>Kecamatan
                                </th>
                                <th class="border border-gray-300 px-2 py-2 text-center" rowspan="2">Kecamatan</th>
                                <th class="border border-gray-300 px-2 py-2 text-center" rowspan="2">Komoditas</th>
                                <th class="border border-gray-300 px-2 py-2 text-center" colspan="12">PRODUKSI/PANEN (Kg)
                                </th>
                                <th class="border border-gray-300 px-2 py-2 text-center" rowspan="2">Jumlah</th>
                            </tr>
                            <tr class="bg-gray-100 text-gray-700">
                                @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $bln)
                                    <th class="border border-gray-300 px-2 py-2 text-center">{{ $bln }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $kecamatan => $rows)
                                @foreach ($rows as $i => $row)
                                    <tr class="hover:bg-blue-50/50 transition">
                                        @if ($i === 0)
                                            <td class="border border-gray-200 px-2 py-1.5 text-center align-middle"
                                                rowspan="{{ $rows->count() }}">{{ $no++ }}</td>
                                            <td class="border border-gray-200 px-2 py-1.5 text-center align-middle"
                                                rowspan="{{ $rows->count() }}">{{ $row->kecamatan->kode }}</td>
                                            <td class="border border-gray-200 px-2 py-1.5 text-center align-middle font-medium"
                                                rowspan="{{ $rows->count() }}">{{ $row->kecamatan->nama }}</td>
                                        @endif
                                        <td class="border border-gray-200 px-2 py-1.5">{{ $row->komoditas }}</td>
                                        @foreach (['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $bln)
                                            <td class="border border-gray-200 px-2 py-1.5 text-center">
                                                {{ $row->$bln > 0 ? number_format($row->$bln, 3, ',', '.') : '' }}
                                            </td>
                                        @endforeach
                                        <td
                                            class="border border-gray-200 px-2 py-1.5 text-center font-semibold text-fish-blue">
                                            {{ $row->jumlah > 0 ? number_format($row->jumlah, 3, ',', '.') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rawData = @json($komoditasData);

        const labels = {!! json_encode($chartData['labels']) !!};

        const warnaPalette = [
            '#0284c7', '#f59e0b', '#10b981', '#ef4444',
            '#8b5cf6', '#ec4899', '#14b8a6', '#f97316',
        ];

        // Petakan setiap komoditas ke warna yang tetap berdasarkan urutannya
        const warnaKomoditas = {};
        Object.keys(rawData).forEach((nama, i) => {
            warnaKomoditas[nama] = warnaPalette[i % warnaPalette.length];
        });

        function buildDatasets(komoditasFilter) {
            if (komoditasFilter === 'semua') {
                return Object.entries(rawData).map(([nama, nilaiArr]) => ({
                    label: nama,
                    data: nilaiArr,
                    borderColor: warnaKomoditas[nama],
                    backgroundColor: warnaKomoditas[nama] + '22',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 4,
                }));
            } else {
                const nilaiArr = rawData[komoditasFilter] ?? Array(12).fill(0);
                const warna = warnaKomoditas[komoditasFilter] ?? '#0284c7';
                return [{
                    label: komoditasFilter,
                    data: nilaiArr,
                    borderColor: warna,
                    backgroundColor: warna + '22',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                }];
            }
        }

        const ctx = document.getElementById('produksiChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: buildDatasets('semua'),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            },
                            padding: 10,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('id-ID')} Ton`
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 0,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Produksi (Ton)',
                            font: {
                                size: 11
                            },
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                        }
                    }
                }
            }
        });

        document.getElementById('filterKomoditas').addEventListener('change', function() {
            chart.data.datasets = buildDatasets(this.value);
            chart.update();
        });
    </script>
@endsection
