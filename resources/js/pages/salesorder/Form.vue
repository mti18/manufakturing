<template>
  <form class="card mb-12" id="form-salesorder" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            salesorder?.uuid
              ? `Edit Sales Order : ${salesorder.name}`
              : "Tambah Sales Order"
          }}
        </h3>
        <button
          type="button"
          class="btn btn-light-danger btn-sm ms-auto"
          @click="($parent.openForm = false), ($parent.selected = undefined)"
        >
          <i class="las la-times-circle"></i>
          Batal
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Nama Perusahaan :
            </label>
            <select2
              name="profile_id"
              id="profile"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.profile_id"
              @change="perusahaan($event), getnumber()"
            >
              <option value="" disabled>Pilih</option>
              <option
                v-for="profile in profiles"
                :value="profile.id"
                :key="profile.uuid"
              >
                {{ profile.nama }}
              </option>
            </select2>
          </div>

          <div v-if="form.profile_id != ''">
            <div class="mb-8">
              <label for="name" class="form-label"> Provinsi : </label>
              <input
                type="text"
                name="perusahaan_provinsi"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!profile_so.provinsi"
                v-model="profile_so.provinsi.nm_provinsi"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> Kota : </label>
              <input
                type="text"
                name="perusahaan_kota"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!profile_so.kota"
                v-model="profile_so.kota.nm_kab_kota"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> Kecamatan : </label>
              <input
                type="text"
                name="perusahaan_kecamatan"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!profile_so.kecamatan"
                v-model="profile_so.kecamatan.nm_kecamatan"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> Keluarahan : </label>
              <input
                type="text"
                name="perusahaan_kelurahan"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!profile_so.kelurahan"
                v-model="profile_so.kelurahan.nm_kelurahan"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> Alamat : </label>
              <textarea
                class="form-control"
                name="perusahaan_alamat"
                v-model="profile_so.alamat"
                placeholder="Alamat Perusahaan"
                disabled
              ></textarea>
            </div>
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Nama Pemesan :
            </label>
            <select2
              name="supplier_id"
              id="supplier"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.supplier_id"
              @change="customer($event)"
            >
              <option value="" disabled>Pilih</option>
              <option
                v-for="customer in customers"
                :value="customer.id"
                :key="customer.uuid"
              >
                {{ customer.nama }}
              </option>
            </select2>
          </div>
          <div v-if="form.supplier_id != ''">
            <div class="mb-8">
              <label for="name" class="form-label"> Nomor Telepon : </label>
              <input
                type="text"
                name="customer_telepon"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!customer_so"
                v-model="customer_so.telepon"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> NPWP : </label>
              <input
                type="text"
                name="customer_npwp"
                id="name"
                class="form-control"
                autoComplete="off"
                disabled
                v-if="!!customer_so"
                v-model="customer_so.npwp"
              />
            </div>

            <div class="mb-8">
              <label for="name" class="form-label"> Alamat : </label>
              <textarea
                class="form-control"
                name="customer_alamat"
                v-if="!!customer_so"
                v-model="customer_so.alamat"
                placeholder="Alamat Perusahaan"
                disabled
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <hr style="margin-top: 2rem; margin-bottom: 3rem" />
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Diketahui Oleh :
            </label>
            <select2
              name="diketahui_oleh"
              id="diketahui_oleh"
              class="form-control"
              autoComplete="off"
              v-model="form.diketahui_oleh"
            >
              <option value="" disabled>Pilih</option>
              <option v-for="user in users" :value="user.id" :key="user.uuid">
                {{ user.name }}
              </option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required">
              Bukti Pemesanan :
            </label>
            <input
              type="text"
              name="bukti_pesan"
              id="bukti_pesan"
              placeholder="Bukti Pemasanan"
              class="form-control"
              autoComplete="off"
              v-model="form.bukti_pesan"
            />
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required">
              Tanggal Pesan :
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" style="padding-block: 1rem"
                  ><i class="far fa-calendar-alt fa-1x"></i
                ></span>
              </div>
              <datepicker
                name="tgl_pesan"
                id="tgl_pesan"
                placeholder="Pilih Tanggal"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.tgl_pesan"
              />
            </div>
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required">
              Tanggal Pengiriman :
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" style="padding-block: 1rem"
                  ><i class="far fa-calendar-alt fa-1x"></i
                ></span>
              </div>
              <datepicker
                name="tgl_pengiriman"
                id="tgl_pengiriman"
                placeholder="Pilih Tanggal"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.tgl_pengiriman"
              />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required">
              Jumlah Paket :
            </label>
            <input
              type="number"
              name="jumlah_paket"
              id="jumlah_paket"
              placeholder="Jumlah Paket"
              class="form-control"
              autoComplete="off"
              v-model="form.jumlah_paket"
            />
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required">
              Jenis Pembayaran :
            </label>
            <select2
              name="jenis_pembayaran"
              id="jenis_pembayaran"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.jenis_pembayaran"
            >
              <option value="Tunai">Tunai</option>
              <option value="Cek">Cek</option>
              <option value="Transfer">Transfer</option>
            </select2>
          </div>
          <div class="mb-8" v-if="form.jenis_pembayaran == 'Transfer'">
            <label for="code" class="form-label required"> Bank : </label>
            <select2
              name="account_id"
              id="account_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.account_id"
            >
              <option disabled>Pilih</option>
              <option
                v-for="account in accounts"
                :value="account.id"
                :key="account.uuid"
              >
                {{ account.nm_account }}
              </option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required">
              Jatuh Tempo :
            </label>
            <div class="input-group">
              <input
                type="number"
                name="tempo"
                id="tempo"
                placeholder="Jatuh Tempo"
                class="form-control"
                required
                autoComplete="off"
                v-model="form.tempo"
              />
              <div class="input-group-append">
                <span class="input-group-text">Hari</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm me-auto mt-8 d-block"
          >
            <i class="la la-arrow-circle-right"></i>
            Next
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- BUTTON LIST -->
  <div class="card-header kedua">
    <div class="card-title w-100">
      <button
        type="button"
        class="btn btn-primary btn-sm btn-elevate btn-icon-sm me-2"
        @click="($parent.openForm = false), ($parent.selected = undefined)"
      >
        <i class="las la-camera-retro"></i>
        Scan QR Code
      </button>
      <button
        type="button"
        class="btn btn-primary btn-sm btn-elevate btn-icon-sm"
        @click="($parent.openForm = false), ($parent.selected = undefined)"
      >
        <i class="las la-pen"></i>
        Typing Code
      </button>
      <button
        type="button"
        class="btn btn-primary btn-sm btn-elevate btn-icon-sm pojok"
        @click="($parent.openForm = false), ($parent.selected = undefined)"
      >
        <i class="las la-copy"></i>
        Salin Pesanan
      </button>
    </div>
  </div>

  <form
    v-if="!!selected"
    class="card mb-12"
    id="form-salesorderdetail"
    @submit.prevent="onSubmit"
  >
    <div class="detail">
      <div class="col">
        <button
          type="submit"
          class="btn btn-primary btn-sm me-auto mt-8 ms-4"
          @click.prevent="tambahBarangJadi()"
        >
          <i class="la la-arrow-circle-right"></i>
          Tambah Barang Jadi
        </button>

        <button
          type="submit"
          class="btn btn-primary btn-sm me-auto mt-8 ms-4"
          @click.prevent="tambahBarangMentah()"
        >
          <i class="la la-arrow-circle-right"></i>
          Tambah Barang Mentah
        </button>
      </div>
      <div class="table-responsive mt-5">
        <table class="table border">
          <thead>
            <tr class="fw-bold fs-6 text-gray-800 border align-middle">
              <!-- <th rowspan="2">Tipe Barang</th>-->
              <th rowspan="2" class="ps-13 pe-13" width="200px">Nama Barang</th>
              <th colspan="2">Qty</th>
              <th rowspan="2">Harga</th>
              <th rowspan="2">Diskon</th>
              <th rowspan="2">Jumlah</th>
              <th rowspan="2">Keterangan</th>
              <th rowspan="2" class="pe-4 ps-3">Aksi</th>
            </tr>
            <tr class="fw-bold fs-6 text-gray-800 border">
              <th>Volume</th>
              <th width="130px">Satuan</th>
            </tr>
          </thead>
          <tbody class="border align-middle">
            <tr v-for="(item, index) in form.barangjadi">
              <td>
                <select2
                  name="barangjadi_id"
                  :id="'barangjadi_id' + index"
                  class="form-control ms-2"
                  required
                  autoComplete="off"
                  @change="getSatuanJadi(index, $event), getHargaBJ(index)"
                  v-model="item.barangjadi_id"
                >
                  <option disabled value="">Pilih barang jadi</option>
                  <option
                    v-for="item in barangjadis"
                    :value="item.id"
                    :key="item.id"
                    :disabled="
                      form.barangjadi.findIndex(
                        (cat) => cat.barangjadi_id == item.id
                      ) == -1
                        ? false
                        : true
                    "
                  >
                    {{ item.nm_barang_jadi }}
                  </option>
                </select2>
              </td>

              <td>
                <input
                  type="text"
                  name="volume"
                  id="volume"
                  style="width: 100px"
                  class="form-control"
                  placeholder="Volume"
                  required
                  autoComplete="off"
                  @input="hitungnilaijadi($event, index)"
                  v-model="item.volume"
                />
              </td>
              <td>
                <select2
                  class="form-control satuan"
                  name="satuan"
                  :id="'nm_satuan_jadi' + index"
                  placeholder="Pilih"
                  v-model="item.satuan"
                  @change="hitungnilaijadi($event, index)"
                  required
                >
                  <option value="" disabled>Pilih</option>
                  <option
                    v-for="satuan in item.satuanjadi"
                    :value="satuan.id"
                    :key="satuan.id"
                  >
                    {{ satuan.nm_satuan_jadi_children }}
                  </option>
                </select2>
              </td>
              <td>
                <money3
                  v-model="item.harga"
                  id="harga"
                  class="form-control"
                  type="text"
                  name="harga"
                  v-bind="config"
                  @input.prevent="hitungjumlahjadi($event, index)"
                  required
                ></money3>
              </td>
              <td>
                <money3
                  v-model="item.diskon"
                  id="diskon"
                  class="form-control"
                  type="text"
                  name="diskon"
                  v-bind="config"
                  @input.prevent="hitungjumlahjadi($event, index)"
                  required
                ></money3>
              </td>
              <td>
                <money3
                  v-model="item.jumlah"
                  id="jumlah"
                  class="form-control"
                  type="text"
                  name="jumlah"
                  v-bind="config"
                  @change="getTotal()"
                  disabled
                  required
                ></money3>
              </td>
              <td>
                <textarea
                  type="text"
                  name="keterangan"
                  id="keterangan"
                  class="form-control"
                  placeholder="Keterangan"
                  autoComplete="off"
                  v-model="item.keterangan"
                />
              </td>
              <td class="pe-6 ps-1">
                <a href="javascript:void(0)">
                  <i
                    class="la la-trash icon-lg text-danger ms-5"
                    @click.prevent="hapusBarangJadi(index)"
                    style="font-size: 22px"
                  ></i>
                </a>
              </td>
            </tr>

            <tr v-for="(item, index) in form.barangmentah">
              <td>
                <select2
                  name="barangmentah_id"
                  :id="'barangmentah_id' + index"
                  class="form-control ms-2"
                  required
                  autoComplete="off"
                  @change="getSatuanMentah(index, $event), getHargaBM(index)"
                  v-model="item.barangmentah_id"
                >
                  <option value="" disabled>Pilih barang mentah</option>
                  <option
                    v-for="item in barangmentahs"
                    :value="item.id"
                    :key="item.id"
                    :disabled="
                      form.barangmentah.findIndex(
                        (cat) => cat.barangmentah_id == item.id
                      ) == -1
                        ? false
                        : true
                    "
                  >
                    {{ item.nm_barangmentah }}
                  </option>
                </select2>
              </td>

              <td>
                <input
                  type="text"
                  name="volume"
                  id="volume"
                  class="form-control"
                  style="width: 100px"
                  placeholder="Volume"
                  required
                  autoComplete="off"
                  v-model="item.volume"
                />
              </td>
              <td>
                <select2
                  class="form-control satuan"
                  name="satuan"
                  placeholder="Pilih"
                  :id="'nm_satuan_mentah' + index"
                  v-model="item.satuan"
                  required
                >
                  <option value="" disabled>Pilih</option>
                  <option
                    v-for="satuan in item.satuanmentah"
                    :value="satuan.id"
                    :key="satuan.id"
                  >
                    {{ satuan.nm_satuan_children }}
                  </option>
                </select2>
              </td>
              <td>
                <money3
                  v-model="item.harga"
                  id="harga"
                  class="form-control"
                  type="text"
                  name="harga"
                  @input="hitungbarangmentah(index)"
                  v-bind="config"
                  required
                ></money3>
              </td>
              <td>
                <money3
                  v-model="item.diskon"
                  id="diskon"
                  class="form-control"
                  type="text"
                  name="diskon"
                  @input="hitungbarangmentah(index)"
                  v-bind="config"
                  required
                ></money3>
              </td>
              <td>
                <money3
                  v-model="item.jumlah"
                  id="jumlah"
                  class="form-control"
                  type="text"
                  name="jumlah"
                  v-bind="config"
                  @change="getTotal()"
                  disabled
                  required
                ></money3>
              </td>
              <td>
                <textarea
                  type="text"
                  name="keterangan"
                  id="keterangan"
                  class="form-control"
                  placeholder="Keterangan"
                  autoComplete="off"
                  v-model="item.keterangan"
                />
              </td>
              <td class="pe-6 ps-1">
                <a href="javascript:void(0)" class="d-inline-block">
                  <i
                    @click.prevent="hapusBarangMentah(index)"
                    class="la la-trash icon-lg text-danger ms-5"
                    style="font-size: 22px"
                  ></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <hr style="margin-top: 2rem; margin-bottom: 3rem" />

    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required"> Keterangan : </label>
            <textarea
              rows="10"
              name="keterangan"
              id="keterangan"
              placeholder="Keterangan"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.keterangan"
            >
            </textarea>
          </div>
        </div>
        <div class="col-6">
          <div class="mt-8">
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label for="code" class="form-label">Total</label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <money3
                      v-model="form.total"
                      id="total"
                      class="form-control"
                      type="text"
                      name="total"
                      @change="hitungnetto()"
                      v-bind="config"
                      readonly
                      required
                    ></money3>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row radio-inline">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                  <div class="input-group">
                    <label for="code" class="form-label">
                      <input
                        type="radio"
                        name="tipe_diskon"
                        id="tipe_diskon"
                        autoComplete="off"
                        value="persen"
                        v-model="form.tipe_diskon"
                      />
                      Persen (%)
                    </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="input-group">
                    <label for="code" class="form-label">
                      <input
                        type="radio"
                        name="tipe_diskon"
                        id="tipe_diskon"
                        autoComplete="off"
                        value="rupiah"
                        v-model="form.tipe_diskon"
                      />
                      Rupiah (Rp)
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label for="code" class="form-label required">Diskon</label>
                </div>
                <div class="col-md-10">
                  <div class="input-group" v-if="form.tipe_diskon == 'persen'">
                    <input
                      type="text"
                      name="diskon"
                      id="diskon"
                      @input="hitungnetto()"
                      class="form-control"
                      required
                      autoComplete="off"
                      v-model="form.diskon"
                    />
                    <div class="input-group-append">
                      <span class="input-group-text">%</span>
                    </div>
                  </div>
                  <div
                    class="input-group"
                    v-else-if="form.tipe_diskon == 'rupiah'"
                  >
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <money3
                      id="diskon"
                      class="form-control"
                      type="text"
                      name="diskon"
                      @input="hitungnetto()"
                      v-bind="config"
                      v-model="form.diskon"
                      required
                    ></money3>
                  </div>
                  <div class="input-group" v-else>
                    <input
                      type="text"
                      name="diskon"
                      id="diskon"
                      class="form-control"
                      disabled
                      autoComplete="off"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label class="form-label required">Uang Muka</label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <money3
                      v-model="form.uang_muka"
                      id="uang_muka"
                      class="form-control"
                      type="text"
                      name="uang_muka"
                      @input="hitungnetto()"
                      v-bind="config"
                      required
                    ></money3>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label class="form-label required"> PPH : </label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <input
                      type="text"
                      name="pph"
                      id="pph"
                      placeholder="pph"
                      class="form-control"
                      required
                      @input="hitungnetto()"
                      autoComplete="off"
                      v-model="form.pph"
                    />
                    <div class="input-group-append">
                      <span class="input-group-text">%</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label for="code" class="form-labe required"> PPN : </label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <input
                      type="text"
                      name="ppn"
                      id="ppn"
                      placeholder="PPN"
                      class="form-control"
                      required
                      @input="hitungnetto()"
                      autoComplete="off"
                      v-model="form.ppn"
                    />
                    <div class="input-group-append">
                      <span class="input-group-text">%</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <div class="row">
                <div class="col-md-2">
                  <label for="code" class="form-label"> Netto : </label>
                </div>
                <div class="col-md-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <money3
                      v-model="form.netto"
                      id="netto"
                      class="form-control"
                      type="text"
                      name="netto"
                      v-bind="config"
                      readonly
                    ></money3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm me-auto mt-8 d-block"
          >
            <i class="la las la-save"></i>
            Simpan
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import { ref } from "vue";
import { useQuery, useMutation } from "vue-query";
import axios from "@/libs/axios";
import { useQueryClient } from "vue-query";
import { Money3Component } from "v-money3";
import { Money3Directive } from "v-money3";

export default {
  components: { money3: Money3Component },
  directives: { money3: Money3Directive },
  data() {
    return {
      profile_so: {},
      customer_so: {},
      config: {
        prefix: "",
        suffix: "",
        thousands: ".",
        decimal: ",",
        precision: 2,
        disableNegative: false,
        disabled: false,
        min: null,
        max: null,
        allowBlank: false,
        minimumNumberOfCharacters: 0,
      },
    };
  },
  props: {
    selected: {
      type: String,
      default: null,
    },
  },
  setup(props) {
    const queryClient = useQueryClient();
    const form = ref({});
    const selected = ref(props.selected);

    const { data: profiles = [] } = useQuery(["profiles"], () =>
      axios.get("/profile/get").then((res) => res.data)
    );
    const { data: customers = [] } = useQuery(["customer"], () =>
      axios.get("/customer/get").then((res) => res.data)
    );
    const { data: barangjadis = [] } = useQuery(["barang_jadis"], () =>
      axios.get("/barangjadi/get").then((res) => res.data)
    );
    const { data: barangmentahs = [] } = useQuery(["barang_mentahs"], () =>
      axios.get("/barangmentah/get").then((res) => res.data)
    );
    const { data: users = [] } = useQuery(["users"], () =>
      axios.get("/user/get").then((res) => res.data)
    );
    const { data: accounts = [] } = useQuery(["accounts"], () =>
      axios.get("/account/get").then((res) => res.data)
    );

    const { data: salesorder = { details: [] }, refetch } = useQuery(
      ["salesorder", selected, "edit"],
      () => {
        // setTimeout(() => KTApp.block("#form-salesorder"), 100);
        return axios
          .get(`/salesorder/${selected.value}/edit`)
          .then((res) => res.data);
      },

      {
        enabled: !!selected.value,
        cacheTime: 0,
        onSuccess: ({ data }) => {
          const datas = { ...data.data };
          console.log(datas);
          if (datas.barangjadi.length) {
            datas.barangjadi.push();
          }
          if (datas.barangmentah.length) {
            datas.barangmentah.push();
          }
          form.value = datas;
        },
        onError: (error) => console.log(error),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected.value
              ? `/salesorder/${selected.value}/update`
              : "/salesorder/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-salesorder");
        },
        onError: (error) => {
          toastr.error(error);
          KTApp.unblock("#form-salesorder");
        },
        onSettled: () => {
          KTApp.unblock("#form-salesorder");
        },
        onSuccess: (data) => {
          KTApp.unblock("#form-salesorder");
          const datas = { ...data.data };
          // if (!datas.barangjadi.length) {
          //   datas.barangjadi.push();
          // }
          // if (!datas.barangmentah.length) {
          //   datas.barangmentah.push();
          // }
          selected.value = datas.uuid;
          form.value = datas;
        },
      }
    );

    return {
      salesorder,
      barangjadis,
      barangmentahs,
      profiles,
      accounts,
      customers,
      users,
      submit,
      form,
      queryClient,
      selected,
      refetch,
    };
  },
  methods: {
    customer(e) {
      var app = this;
      if (!app.customers) return;

      var index = app.customers.findIndex((cat) => cat.id == e);

      app.customer_so = app.customers[index];
    },

    perusahaan(e) {
      var app = this;
      if (!app.profiles) return;

      var index = app.profiles.findIndex((cat) => cat.id == e);

      app.profile_so = app.profiles[index];
    },

    clearFormData() {
      this.form = {
        profile_id: "",
        supplier_id: "",
        tgl_pesan: "",
        tgl_pengiriman: "",
        jenis_pembayaran: "",
        diketahui_oleh: "",
        account_id: "",
        tempo: 0,
      };
    },

    hitungnilaijadi(e, index) {
      var app = this;

      var volume = app.form.barangjadi[index].volume;
      var indexsatuan = app.form.barangjadi[index].satuanjadi.findIndex(
        (cat) => cat.id == e
      );

      var nilai = app.form.barangjadi[index].satuanjadi[indexsatuan].nilai;
      app.form.barangjadi[index].nilaitotal = volume * nilai;

      // var jumlah =
      //   volume * nilai * parseFloat(app.form.barangjadi[index].harga) -
      //   parseFloat(app.form.barangjadi[index].diskon);

      // app.form.barangjadi[index].jumlah = jumlah;
    },

    hitungjumlahjadi(e, index) {
      var app = this;
      var jumlah =
        app.form.barangjadi[index].nilaitotal *
          parseFloat(app.form.barangjadi[index].harga) -
        parseFloat(app.form.barangjadi[index].diskon);

      app.form.barangjadi[index].jumlah = jumlah;
    },

    hitungbarangmentah(index) {
      var app = this;
      var jumlah =
        parseFloat(app.form.barangmentah[index].harga) -
        parseFloat(app.form.barangmentah[index].diskon);

      app.form.barangmentah[index].jumlah = jumlah;
      // app.form.total = app.form.barangjadi[index].jumlah;
    },

    getTotal() {
      var app = this;
      var jumlah = 0;

      for (let i = 0; i < app.form.barangmentah.length; i++) {
        jumlah += parseFloat(app.form.barangmentah[i].jumlah);
      }

      for (let i = 0; i < app.form.barangjadi.length; i++) {
        jumlah += parseFloat(app.form.barangjadi[i].jumlah);
      }

      // console.log(jumlah);
      app.form.total = jumlah;
    },

    hitungnetto() {
      var app = this;
      // console.log(app.form);

      if (app.form.tipe_diskon == "persen") {
        var uang_muka =
          parseFloat(app.form.total) -
          (parseFloat(app.form.total) * parseFloat(app.form.diskon)) / 100 -
          parseFloat(app.form.uang_muka);

        var pph = uang_muka + (uang_muka * parseFloat(app.form.pph)) / 100;
        var ppn = pph + (pph * parseFloat(app.form.ppn)) / 100;

        app.form.netto = ppn;
      } else {
        var uang_muka =
          (parseFloat(app.form.total) - parseFloat(app.form.diskon) || 0) -
          parseFloat(app.form.uang_muka);
        var pph = uang_muka + (uang_muka * parseFloat(app.form.pph)) / 100;
        var ppn = pph + (pph * parseFloat(app.form.ppn)) / 100;

        app.form.netto = ppn;
      }
    },

    // hitungpajak() {
    //   var app = this;

    //   var ppn = app.form.netto + (app.form.netto * app.form.pph) / 100;
    //   var pph = ppn + (ppn * app.form.ppn) / 100;

    //   app.form.netto = pph;
    // },

    tambahBarangJadi() {
      this.form.barangjadi.push({});
    },
    tambahBarangMentah() {
      this.form.barangmentah.push({});
    },

    hapusBarangJadi(index) {
      this.form.barangjadi.splice(index, 1);
    },

    hapusBarangMentah(index) {
      this.form.barangmentah.splice(index, 1);
    },

    getSatuanMentah(index, satuan_id) {
      setTimeout(() => {
        var app = this;
        var i = app.barangmentahs.findIndex((cat) => cat.id == satuan_id);

        satuan_id = app.barangmentahs[i]?.barangsatuan_id;

        var app = this;
        axios.get(`barangsatuan/${satuan_id}/child`).then((res) => {
          app.form.barangmentah[index].satuanmentah = res.data.data;
        });
      }, 100);
    },

    getSatuanJadi(index, satuan_id) {
      setTimeout(() => {
        var app = this;
        var i = app.barangjadis.findIndex((cat) => cat.id == satuan_id);

        satuan_id = app.barangjadis[i]?.barangsatuanjadi_id;

        var app = this;
        axios.get(`barangsatuanjadi/${satuan_id}/child`).then((res) => {
          app.form.barangjadi[index].satuanjadi = res.data.data;
        });
      }, 100);
    },

    getHargaBJ(index) {
      setTimeout(() => {
        var app = this;

        app.form.barangjadi[index].harga = app.barangjadis[index].harga;
      }, 100);
    },

    getHargaBM(index) {
      setTimeout(() => {
        var app = this;

        app.form.barangmentah[index].harga = app.barangmentahs[index].harga;
      }, 100);
    },

    getnumber() {
      this.$http
        .get("salesorder/getnumber")
        .then((res) => {
          this.form.no_pemesanan = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      vm.form.detail = {
        barangmentah: vm.form.barangmentah ?? [],
        barangjadi: vm.form.barangjadi ?? [],
      };

      vm.form.tgl_pesan = $("#tgl_pesan").val();
      vm.form.tgl_pengiriman = $("#tgl_pengiriman").val();
      // const data = new FormData(document.getElementById("form-salesorder"));
      this.submit(vm.form, {
        onSuccess: (data) => {
          toastr.success(data.message);
          // vm.selected = data.data.uuid;
          vm.$parent.openForm = true;
          vm.$parent.selected = undefined;
          vm.refetch();
          vm.queryClient.invalidateQueries(["/salesorder/paginate"], {
            exact: true,
          });
        },
      });
    },
  },

  mounted() {
    this.clearFormData();
  },
};
</script>

<style scoped>
.pojok {
  margin-left: 630px;
}

.kedua {
  border-bottom: 0px solid #e2e8f0;
}

.table {
  vertical-align: middle;
  text-align: center;
}
</style>
