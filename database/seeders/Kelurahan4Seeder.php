<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelurahan;

class Kelurahan4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelurahans');
        $data = [
			['kecamatan_id' => 9471010, 'nm_kelurahan' => 'Koya Timur'],
			['kecamatan_id' => 9471010, 'nm_kelurahan' => 'Skow Mabo'],
			['kecamatan_id' => 9471010, 'nm_kelurahan' => 'Skow Sae'],
			['kecamatan_id' => 9471010, 'nm_kelurahan' => 'Koya Tengah'],
			['kecamatan_id' => 9471010, 'nm_kelurahan' => 'Kampung Mosso'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Asano'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Nafri'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Engros'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Awiyo'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Koya Koso'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Yobe'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Abe Pantai'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Kota Baru'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Wai Mhorock'],
			['kecamatan_id' => 9471020, 'nm_kelurahan' => 'Wahno'],
			['kecamatan_id' => 9471021, 'nm_kelurahan' => 'Yoka'],
            ['kecamatan_id' => 9471021, 'nm_kelurahan' => 'Kampung Waena'],
			['kecamatan_id' => 9471021, 'nm_kelurahan' => 'Hedam'],
			['kecamatan_id' => 9471021, 'nm_kelurahan' => 'Waena'],
			['kecamatan_id' => 9471021, 'nm_kelurahan' => 'Yabansai'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Entrop'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Tobati'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Hamadi'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Ardipura'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Numbai'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Argapura'],
			['kecamatan_id' => 9471030, 'nm_kelurahan' => 'Tahima Soroma'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Gurabesi'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Bayangkara'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Mandala'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Trikora'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Angkasapura'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Tanjung Ria'],
			['kecamatan_id' => 9471040, 'nm_kelurahan' => 'Kampung Kayobatu']
        ];

        foreach ($data as $value) {
            Kelurahan::create($value);
        }
    }
}
