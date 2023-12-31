<html>
	<title>Sales Order</title>
<head>
	<style>
		@page { margin: 0.7cm 0.9cm;}
		body{
			background-color: #FFF;
			margin: 0px;
			padding: 0px;
			font-size: 11px;
			font-family: times-roman;
		}
        
		.main{
			background-color: white;
			width: 19cm;
		}
		.table-main{
			width: 100%;
			overflow: hidden;
			border-collapse: collapse;
		}
		#verimer{
			width: 170px;
		}
		.profile-img{
			text-align: center;
		}
		.profile-nama{
			font-weight: bold;
			font-size: 14px;
			text-align: center;
		}
		.profile-alamat{
			font-size: 11px;
			text-align: center;
		}
		.profile-telepon{
			font-size: 11px;
			text-align: center;
		}
		.pdf-title{
			font-size: 23px;
			font-weight: bold;
			text-align: center;
		}
		.pdf-desc{
			font-size: 11px;
			text-align: center;
		}
		.table-data{
			border-collapse: collapse;
			padding-top: 25px;
		}
		.table-data .nomor{
			text-align: center!important;
		}
		.table-ttd{
			border-collapse: collapse;
			margin-left: auto;

		}
		.title-ttd{
			width: 100px;
			text-align: center;
		}
		.body-ttd{
			width: 120px;
			height: 80px;
		}
		.tanggal-ttd{
			padding: 3px 70px;
		}
		.geser{
			color: white;
			width: 130px;
		}
		.white{
			color: white;
		}
		.no-border{
			border: 1px solid white;
		}
		.label{
			padding-left: 3px;
			font-size: 12px;
			font-weight: 700;
		}
		.info-row{
			vertical-align: top;
			display: inline-block;
			padding: 5px 0px;
			width: 49%;
		}
		.table-data .nomor{
			text-align: center!important;
		}
		.table-data tbody tr td{
			text-align: center;
			vertical-align: middle;
		}
		.table-data tbody tr > .barang{
			text-align: left!important;
		}
		.text-detail{
			text-align: left;
		}
		.keterangan{
			text-align: left;
			border: 2px solid #B22222;
			color: #d93636;
			margin-top: 30px;
			width: 380px;
			height: 80px;
			position: absolute;
		}
		.dts-1{ width: 5%; }
		.dts-2{ width: 35%; }
		.dts-3{ width: 15%; }
		.dts-4{ width: 15%; }
		.dts-5{ width: 15%; }
		.dts-6{ width: 15%; }
		.dts-7{ width: 20%; }
		.bold{
			text-align: left;
			font-weight: bold;
		}

		.ttd-img{
			position: absolute;
			top: -40px;
			left: 15px;
			width: 90px;
			height: 70px;
		}
		.ttd-name{
			font-weight: 400;
			position: absolute;
			height: 12px;
			width: 120px;
			top: 27px;
		}
	</style>
</head>

<body>
	<div class="main">
		<table class="table-main">
			<tr class="row-1">
				<td class="col-title" width="180">
					
					<div class="profile-img"><img src="{{ public_path($data->profile->logo) }}" alt="logo perusahaan" id="verimer"></div>
					<div class="profile-nama">{{ $data->profile->nama }}</div>
				</td>
				<td class="col-title" width="200">
					<div class="pdf-title" style="margin-top: -10px">SURAT PEMBELIAN</div>
					<div class="pdf-desc" align="center" style="padding-left: 60px">
						<table>
							<tr>
								<td>No Surat Pembelian</td>
								<td>:</td>
								<td>{{ $data->no_surat }}</td>
							</tr>
						</table>
					</div>
				</td>
				<td class="col-title" width="150" style="vertical-align: top; text-align: right;">
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top">
					<div class="profile-alamat">	
							<span>{{ $data->profile->alamat}}</span>					
							<span>Kec. {{ $data->profile->kecamatan->nm_kecamatan}}, Kel. {{ $data->profile->kelurahan->nm_kelurahan}} , Kota {{ $data->profile->kota->nm_kab_kota }} </span>
					</div>
					<div class="profile-telepon">
						<div>Telp : {{ $data->profile->telepon }} </div>
					</div>
				</td>
				<td width="300" style="vertical-align: top; text-align: right;">
					<div>Tanggal : {{ $data->tgl_permintaan }}</div>
				</td>
			</tr>
		</table>
		<hr style="width:100%;margin-left:0">
		<table>
			<tr>
				<td>
					Supplier
					<table>
						<tr>
							<td>Nama Supplier</td>
							<td>:</td>
							<td>{{ $data->supplier->nama}}</td>
						</tr>
						<tr>
							<td>Telepon</td>
							<td>:</td>
							<td>{{ $data->supplier->telepon}}</td>
						</tr>
						<tr>
							<td rowspan="2">Alamat</td>
							<td rowspan="2">:</td>
							<td rowspan="2">
								<span>{{ $data->supplier->alamat }}, Kec.</span>
								<span>{{ $data->supplier->kecamatan->nm_kecamatan }}, </span>
								<span> Kota {{ $data->supplier->kota->nm_kab_kota }} </span>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<br>
					<table>
						<tr>
							<td>Tanggal</td>
							<td>:</td>
							<td>{{ $data->tgl_po}}</td>
						</tr>
						<tr>
							<td>Bukti Permintaan</td>
							<td>:</td>
							<td>{{ $data->bukti_permintaan }} </td>
						</tr>
						<tr>
							<td rowspan="2">Alamat Pengiriman</td>
							<td rowspan="2">:</td>
							<td rowspan="2">
								<span>{{ $data->profile->alamat }}, Kec.</span>
								<span>{{ $data->profile->kecamatan->nm_kecamatan }}, Kel. </span>
								<span> {{ $data->profile->kelurahan->nm_kelurahan }},</span>
								<span> Kota {{ $data->profile->kota->nm_kab_kota }} </span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
		<table  border="1" class="table-data" width="100%">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nama Barang</th>
					<th	colspan="2">Qty</th>
					<th rowspan="2">Harga Satuan</th>
					<th rowspan="2">Jumlah Harga</th>
				</tr>
				<tr>
					<th>Jumlah</th>
					<th>Satuan</th>
				</tr>
			</thead>
			<tbody>
            <?php $urut=0; ?>
				@foreach($data->details as $datas)
					<?php $urut++; ?>
				<tr>
					@if($datas->permintaan->tipe_barang=="barang_jadi")
						<td class="dts-1 nomor">{{ $urut }}</td>
						<td class="dts-2 barang"> {{ $datas->permintaan->barang_jadi->nm_barang_jadi}}</td>
						<td class="dts-3"> {{ $datas->permintaan->volume}}</td>
						<td class="dts-4"> {{ $datas->permintaan->barang_jadi->barangsatuanjadi->nm_satuan_jadi}}</td>
						<td class="dts-5">Rp. {{ number_format("$datas->harga",2,",",".") }}</td>
						<td class="dts-6">Rp. {{ number_format("$datas->jumlah",2,",",".") }}</td>
					@else
						<td class="dts-1 nomor">{{ $urut }}</td>
						<td class="dts-2 barang"> {{ $datas->permintaan->barang_mentah->nm_barangmentah }}</td>
						<td class="dts-3"> {{ $datas->permintaan->volume}}</td>
						<td class="dts-4"> {{ $datas->permintaan->barang_mentah->barangsatuan->nm_satuan_children }}</td>
						<td class="dts-4">Rp. {{ number_format("$datas->harga",2,",",".") }}</td>
						<td class="dts-6">Rp. {{ number_format("$datas->jumlah",2,",",".") }}</td>
					@endif
				</tr>
                @endforeach
			</tbody>
		</table>
		<table border="1" class="table-data" width="120%" style="padding-top: 0px; page-break-inside: avoid;">
			<tbody>
				<tr>
					<td class="no-border dts-1"></td>
					<td class="no-border dts-2"></td>
					<td class="no-border dts-3"></td>
					<td class="no-border dts-4"></td>
					<td class="no-border dts-5"style="border-top: 1px solid black; margin-bottom: 3px"></td>
					<td class="no-border dts-6"style="border-top: 1px solid black; margin-bottom: 3px"></td>
					<td class="no-border dts-7"></td>
				</tr>
				<tr>
					<th class="no-border bold" style="border-right: 1px solid black;" colspan="4" rowspan="6">Note : {{ $data->keterangan }}</th>
					<th class="bold">Jumlah Penjualan</th>
					<td>Rp. {{ number_format("$data->jml_penjualan",2,",",".") }}</td>
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
				<tr>
					<th class="bold">Diskon</th>
					@if($data->diskon <= 100)
						<td>{{ $data->diskon }}%</td>
					@else
						<td>Rp. {{ number_format("$data->diskon",2,",",".") }}</td>
					@endif
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
				<tr>
					<th class="bold">Uang Muka</th>
					<td>Rp. {{ number_format("$data->uangmuka",2,",",".") }}</td>
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
				<tr>
					<th class="bold">Dasar Pengenaan Pajak</th>
					<td>{{ $data->pajak }}%</td>
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
				<tr>
					<th class="bold">PPN</th>
					<td>{{ $data->ppn }}%</td>
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
				<tr>
					<th class="bold">Netto</th>
					<td>Rp. {{ number_format("$data->netto",2,",",".") }}</td>
					<td class="no-border" style="border-left: 1px solid black;"></td>
				</tr>
			</tbody>
		</table>
		<table border="1" style="margin-top: 15px;" class="table-ttd">
			<tr>
				<th class="title-ttd">Dibuat Oleh</th>
				<th class="title-ttd">Mengtahui</th>
				<th class="title-ttd">Disetujui</th>	
			</tr>
			<tr>
				<td style="height: 60px;"><img src="" width="90px" height="70px" style="margin-left: 5px;" ></td>
				<td style="height: 60px;"><img src="{{ (($data->acc_pimpinan != 'N')?public_path($data->profile->ttd):'') }}" width="90px" height="70px" style="margin-left: 5px;" ></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid white; text-align: center;">{{ $data->diketahuioleh->name }}</td>
				<td style="border-top: 1px solid white; text-align: center;">{{ $data->profile->pimpinan }}</td>
			</tr>
		</table>
	</div>
</body>
</html>
