<?php

namespace App\Helpers;

use LogicException;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AppHelper
{
	static function sendOtp($phone)
	{
		$otp = self::generateOTP(6);
		$user = User::where('whatsapp', $phone)->first();

		if (isset($user->id) && $user->active == '0') {
			return RestApi::error('Akun belum aktif. Silakan selesaikan pendaftaran.', 400);
		}

		if (!isset($user->id)) {
			return RestApi::error('Nomor tidak terdaftar.', 400);
		} else if (isset($user->id)) {
			if (strtotime('-90 second', $user->otp_expire) >= strtotime('now')) {
				$wait = (intval(strtotime('-90 second', $user->otp_expire)) - intval(strtotime('now')));
				return RestApi::error('Tunggu ' . $wait . ' detik.', 400, ['resend_time' => $wait]);
			}

			$user->otp = $otp;
			$user->otp_expire = strtotime('+2 minutes');

			if (!$user->update()) {
				return RestApi::error('Sesuatu error terjadi.', 500);
			}
		} else {
			return RestApi::error('Silakan login dengan email dan password.', 500);
		}

		$data = [
			'ApiKey'    => getEnv('WA_KEY'),
			'Phone'     => self::parsePhone($phone),
			'Message'   => str_replace('$DATE$', date('d-m-Y H:i:s', strtotime('+2 minutes')), str_replace('\n', PHP_EOL, str_replace('$OTP$', $otp, getEnv('WA_MESSAGE'))))
		];

		try {
			$response = self::request('POST', getEnv('WA_URL') . 'v5/send', $data);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$response = $e->getResponse();
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			$response = $e->getResponse();
		}

		if ($response->getStatusCode() == 200 && json_decode($response->getBody())->status) {
			return RestApi::success([
				'resend_time' => 30,
				'phone' => $phone,
				'message' => ((json_decode($response->getBody())->message == 'sent') ? 'Berhasil mengirim Kode OTP.' : 'Gagal mengirim Kode OTP.'),
				'type' => 'otp',
			], 200, 'Send OTP success.');
		} else {
			return RestApi::error('Gagal mengirim Kode OTP.', $response->getStatusCode());
		}
	}

	static function generateOTP($n)
	{
		$generator = "1357902468";
		$result = "";

		for ($i = 1; $i <= $n; $i++) {
			$result .= substr($generator, (rand() % (strlen($generator))), 1);
		}

		return $result;
	}

	static function request($method = 'GET', $url = null, $data = [], $headers = [])
	{
		$client = new \GuzzleHttp\Client();

		$response = $client->request($method, $url, [
			'json' => $data,
			'headers' => array_merge([
				'User-Agent' => 'LekJukiApp/' . date('Y'),
			], $headers)
		]);

		return $response;
	}

	static function parsePhone($number = null)
	{
		if ($number == null) {
			return $number;
		}

		$number = str_replace(" ", "", $number);
		$number = str_replace("'", "", $number);
		$number = str_replace("\"", "", $number);
		$number = str_replace("-", "", $number);
		$number = str_replace("(", "", $number);
		$number = str_replace("*", "", $number);
		$number = str_replace("^", "", $number);
		$number = str_replace(")", "", $number);
		$number = str_replace(".", "", $number);
		$number = str_replace(",", "", $number);
		$number = str_replace("/", "", $number);
		$number = str_replace("?", "", $number);

		$number = preg_replace('/[a-z]/i', '', $number);
		// dd($number);
		preg_match_all('!\d+!', $number, $no);
		$no = $no[0][0];


		$phone = null;

		if (substr(trim($no), 0, 1) == '0') {
			$phone = trim($no);
		} elseif (substr(trim($no), 0, 2) == '62') {
			$phone = '0' . substr(trim($no), 2);
		} elseif (substr(trim($no), 0, 3) == '+62') {
			$phone = '0' . substr(trim($no), 3);
		}

		return $phone;
	}

	static function readMore($string, $length = 50)
	{
		if (strlen($string) <= $length) {
			return substr($string, 0, $length);
		} else {
			return substr($string, 0, $length) . "...";
		}
	}

	static function randomColor($awal = 'rgb(', $akhir = ')')
	{
		$rgbColor = [];

		foreach (array('r', 'g', 'b') as $color) {
			$rgbColor[$color] = mt_rand(0, 255);
		}

		return $awal . implode(",", $rgbColor) . $akhir;
	}

	static function cached_asset($path, $bustQuery = false)
	{
		$realPath = public_path($path);
		if (!file_exists($realPath)) {
			throw new LogicException("File not found at [{$realPath}]");
		}
		$timestamp = filemtime($realPath);

		if (!$bustQuery) {
			$extension = pathinfo($realPath, PATHINFO_EXTENSION);
			$stripped = substr($path, 0, - (strlen($extension) + 1));
			$path = implode('.', array($stripped, $timestamp, $extension));
		} else {
			$path  .= '?' . $timestamp;
		}
		return asset($path);
	}

	static function BulanToRomawi($date)
	{
		$array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
		$bln = $array_bln[$date];
		return $bln;
	}

	static function getintbefore($bln)
	{
		$bln = $bln - 1;

		if ($bln == 0) {
			$bln = 12;
		}
		return $bln;
	}

	static function is_base64($s)
	{
		return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
	}

	static function tanggal_indo($tanggal, $cetak_hari = false)
	{
		$hari = array(
			1 =>    'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu',
			'Minggu'
		);

		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$split 	  = explode('-', $tanggal);
		$tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];

		if ($cetak_hari) {
			$num = date('N', strtotime($tanggal));
			return $hari[$num] . ', ' . $tgl_indo;
		}
		return $tgl_indo;
	}

	static function get_bulan($index)
	{
		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		return $bulan[$index];
	}

	static function stringbulantoint($string)
	{
		$bulan = array(
			'January' => 1,
			'February' => 2,
			'March' => 3,
			'April' => 4,
			'May' => 5,
			'June' => 6,
			'July' => 7,
			'August' => 8,
			'September' => 9,
			'October' => 10,
			'November' => 11,
			'December' => 12
		);
		return $bulan[$string];
	}

	static function tgl_indo($tgl)
	{
		$tanggal = substr($tgl, 8, 2);
		switch (substr($tgl, 5, 2)) {
			case '01':
				$bulan = "Januari";
				break;
			case '02':
				$bulan = "Februari";
				break;
			case '03':
				$bulan = "Maret";
				break;
			case '04':
				$bulan = "April";
				break;
			case '05':
				$bulan = "Mei";
				break;
			case '06':
				$bulan = "Juni";
				break;
			case '07':
				$bulan = "Juli";
				break;
			case '08':
				$bulan = "Agustus";
				break;
			case '09':
				$bulan = "September";
				break;
			case '10':
				$bulan = "Oktober";
				break;
			case '11':
				$bulan = "November";
				break;
			case '12':
				$bulan = "Desember";
				break;
		}

		$tahun = substr($tgl, 0, 4);
		return $tanggal . ' ' . $bulan . ' ' . $tahun;
	}

	static function hari_indo($tanggal)
	{
		$hari = array(
			1 =>    'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu',
			'Minggu'
		);
		$num = date('N', strtotime($tanggal));
		return $hari[$num];
	}

	static function tanpa_tahun($tanggal, $cetak_hari = false)
	{
		$hari = array(
			1 =>    'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu',
			'Minggu'
		);

		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$split 	  = explode('-', $tanggal);
		$tgl_indo = $split[2] . ' ' . $bulan[(int)$split[1]];

		if ($cetak_hari) {
			$num = date('N', strtotime($tanggal));
			return $hari[$num] . ', ' . $tgl_indo;
		}
		return $tgl_indo;
	}

	static function TglToText($tgl)
	{
		$split 	  = explode('-', $tgl);
		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$hari = self::hari_indo($tgl);
		$tgl_indo = self::AngkaToText($split[2]);
		$bulan = $bulan[(int)$split[1]];
		$tahun = self::AngkaToText($split[0]);
		return ucfirst($hari) . " tanggal " . ucwords($tgl_indo) . " bulan " . ucfirst($bulan) . " tahun " . ucwords($tahun);
	}

	static function AngkaToText($nilai)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = "" . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = self::AngkaToText($nilai - 10) . " belas";
		} else if ($nilai < 100) {
			$temp = self::AngkaToText($nilai / 10) . " puluh " . self::AngkaToText($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus " . self::AngkaToText($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = self::AngkaToText($nilai / 100) . " ratus " . self::AngkaToText($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu " . self::AngkaToText($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = self::AngkaToText($nilai / 1000) . " ribu " . self::AngkaToText($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = self::AngkaToText($nilai / 1000000) . " juta " . self::AngkaToText($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = self::AngkaToText($nilai / 1000000000) . " milyar " . self::AngkaToText(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = self::AngkaToText($nilai / 1000000000000) . " trilyun " . self::AngkaToText(fmod($nilai, 1000000000000));
		}
		return ucwords($temp);
	}

	static function seo($s)
	{
		$c = array(' ');
		$d = array('-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+');
		$s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
		$s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua

		return $s;
	}

	static function filename($s)
	{
		$c = array(' ');
		$d = array('-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+');
		$s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
		$s = strtoupper(str_replace($c, '_', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua

		return $s;
	}

	static function generateRandomToken()
	{
		return self::generateRandomCharHex(8) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(12);
	}

	static function generateRandomCharHex($a)
	{
		$char = 'ABCDEF1234567890';
		$str = '';

		for ($i = 0; $i < $a; $i++) {
			$pos = rand(0, strlen($char) - 1);
			$str .= $char[$pos];
		}

		return $str;
	}

	static function generateForgotToken()
	{
		$code = '';
		do {
			$code = strtolower(self::generateRandomCharHex(8) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(4) . '-' . self::generateRandomCharHex(12));
			$cek = DB::table('password_resets')->where('token', $code)->count();
		} while (0 < $cek);
		return $code;
	}

	static function rupiah($a, $b = 0)
	{
		return "Rp. " . number_format($a, $b, ',', '.');
	}
	static function rupiahNoRp($a, $b = 0)
	{
		return  number_format($a, $b, ',', '.');
	}
	// ======= fungsi Compress Image  ======= //

	static function m_resize($pathToImages, $pathToThumbs, $thumbWidth, $thumbHeight = 0)
	{

		// parse path for the extension
		$info = pathinfo($pathToImages);
		if (strtolower($info['extension']) == 'jpg') {
			// load image and get image size
			$img = imagecreatefromjpeg($pathToImages);
			$width = imagesx($img);
			$height = imagesy($img);
		} else if (strtolower($info['extension']) == 'jpeg') {
			// load image and get image size
			$img = imagecreatefromjpeg($pathToImages);
			$width = imagesx($img);
			$height = imagesy($img);
		} else if (strtolower($info['extension']) == 'png') {
			// load image and get image size
			$img = imagecreatefrompng($pathToImages);
			$width = imagesx($img);
			$height = imagesy($img);
		}

		// calculate thumbnail size
		$new_width = $thumbWidth;
		if (empty($thumbHeight)) {
			$new_height = floor($height * ($thumbWidth / $width));
		} else {
			$new_height = $thumbHeight;
		}

		// create a new temporary image
		$tmp_img = imagecreatetruecolor($new_width, $new_height);

		// copy and resize old image into new image
		imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		// save thumbnail into a file
		imagejpeg($tmp_img, $pathToThumbs);
	}

	public static function deleteDir($dirPath)
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
}
