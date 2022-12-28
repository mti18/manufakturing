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
			width: 120px;
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
					<div class="pdf-title" style="margin-top: -10px">SALES ORDER</div>
					<div class="pdf-desc" align="center" style="padding-left: 60px">
						<table>
							<tr>
								<td>No SO</td>
								<td>:</td>
								<td>{{ $data->no_pemesanan }}</td>
							</tr>
						</table>
					</div>
				</td>
				<td class="col-title" width="150" style="vertical-align: top; text-align: right;">
					<div>Tanggal : {{ $data->tgl_pesan }}</div>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top">
					<div class="profile-alamat">						
							<span>Kec. {{ $data->profile->kecamatan->nm_kecamatan}}, Kel. {{ $data->profile->kelurahan->nm_kelurahan}} , </span>
							<span>Kota {{ $data->profile->kota->nm_kab_kota }}</span>
					</div>
					<div class="profile-telepon">
						<div>Telp : {{ $data->profile->telepon }} </div>
					</div>
				</td>
				<td>
					<table>
						<tr>
							<td>Nama Pemesan</td>
							<td>:</td>
							<td>{{ $data->supplier->nama}}</td>
						</tr>
						<tr>
							<td>Telepon</td>
							<td>:</td>
							<td>{{ $data->supplier->telepon}}</td>
						</tr>
						<tr>
							<td>NPWP</td>
							<td>:</td>
							<td>{{ (($data->supplier->npwp != null) ? $data->supplier->npwp : '-')  }} </td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>:</td>
							<td>
                            <span>{{ $data->supplier->alamat }}, Kec.</span>
							<span>{{ $data->supplier->kecamatan->nm_kecamatan }}, {{ $data->profile->kota->nm_kab_kota }} </span>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td>Bukti Pemesanan</td>
							<td>:</td>
							<td>{{ $data->bukti_pesan }} </td>
						</tr>
						<tr>
							<td>Tanggal Pesan</td>
							<td>:</td>
							<td>{{ $data->tgl_pesan}}</td>
						</tr>
						<tr>
							<td>Tanggal Pengiriman</td>
							<td>:</td>
							<td>{{ $data->tgl_pengiriman }}</td>
						</tr>
						<tr>
							<td>Jenis Pembayaran</td>
							<td>:</td>
							<td> {{ $data->jenis_pembayaran }}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div class="row-4">
			<div colspan="3">
				<table border="1" class="table-data" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Barang</th>
							<th>Qty</th>
							<th>Harga</th>
							<th>Diskon</th>
							<th>Jumlah</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
                    <?php $urut=0; ?>
						@foreach($data->detail as $datas)
							<?php $urut++; ?>
							<tr>
								@if($datas->barangjadi_id != null)
									<td class="dts-1 nomor">{{ $urut }}</td>
									<td class="dts-2 barang"> {{ $datas->barangjadi->nm_barang_jadi }} ({{ $datas->barangjadi->kd_barang_jadi }})</td>
									<td class="dts-3">{{ $datas->volume }} Buah</td>
									<td class="dts-4">Rp. {{ number_format("$datas->harga",2,",",".") }}</td>
									<td class="dts-5">{{ $datas->diskon }}%</td>
									<td class="dts-6">Rp. {{ number_format("$datas->jumlah",2,",",".") }}</td>
									<td class="dts-7">{{ (($datas->keterangan != null) ? $datas->keterangan : '-') }}</td>
								@else
									<td class="dts-1 nomor">{{ $urut }}</td>
									<td class="dts-2 barang"> {{ $datas->barangmentah->nm_barangmentah }} ({{ $datas->barangmentah->kd_barang_mentah }})</td>
									<td class="dts-3">{{ $datas->volume }}</td>
									<td class="dts-4">Rp. {{ number_format("$datas->harga",2,",",".") }}</td>
									<td class="dts-5">{{ $datas->diskon }}%</td>
									<td class="dts-6">Rp. {{ number_format("$datas->jumlah",2,",",".") }}</td>
									<td class="dts-7">{{ (($datas->keterangan != null) ? $datas->keterangan : '-') }}</td>
								@endif
							</tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>

		<table border="1" style="margin-top: 15px;" class="table-ttd">
			<tr>
				<th class="title-ttd">Dibuat Oleh</th>
				<th class="title-ttd">Mengtahui</th>
				<th class="title-ttd">Disetujui</th>	
			</tr>
			<tr>
				<td style="height: 60px;"><img src="" width="90px" height="70px" style="margin-left: 5px;" ></td>
				<td style="height: 60px;"><img src="" width="90px" height="70px" style="margin-left: 5px;" ></td>
				<td style="height: 60px;"><img src="{{ (($data->acc_pimpinan != 'N')?public_path($data->profile->ttd):'') }}" width="90px" height="70px" style="margin-left: 5px;" ></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid white; text-align: center;">{{ $data->user->name }}</td>
				<td style="border-top: 1px solid white; text-align: center;">{{ $data->diketahuioleh->name }}</td>
				<td style="border-top: 1px solid white; text-align: center;">{{ $data->profile->pimpinan }}</td>
			</tr>
		</table>
	</div>
</body>
</html>
