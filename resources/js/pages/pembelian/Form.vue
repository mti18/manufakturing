<template>
    <form class="card mb-12" id="form-pembelian" @submit.prevent="onSubmit">
      <div class="card-header">
        <div class="card-title w-100">
          <h3>
            {{ pembelian?.uuid ? `Edit Pembelian : ${pembelian.name}` : "Tambah Pembelian"  }}
          </h3>
          <button
            type="button"
            class="btn btn-light-danger btn-sm ms-auto"
            @click="($parent.openForm = false, $parent.selected = undefined)"
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
              <label class="form-label required"> Nama Perusahaan : </label>
              <select2 name="profile_id" id="profile" @change="perusahaan($event)"
                class="form-control" required autoComplete="off" v-model="form.profile_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="profile in profiles" :value="profile.id" :key="profile.uuid">{{ profile.nama }}</option>
              </select2>
            </div>
            <div class="detailperusahaan" v-if="form.profile_id != null">
              <div class="mb-8">
                <label class="form-label required"> Alamat : </label>
                <input type="text" name="alamat" id="alamat"
                  class="form-control" autoComplete="off" disabled v-model="profile_so.alamat" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPWP : </label>
                <input type="text" name="npwp" id="npwp"
                  class="form-control" autoComplete="off" disabled :value="profile_so.npwp || '-'" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Nama Supplier : </label>
              <select2 name="supplier_id" id="supplier"  @change="supplier($event)"
                class="form-control" required autoComplete="off" v-model="form.supplier_id" >
                <option value="" disabled>Pilih</option>
                <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.uuid">{{ supplier.nama }}</option>
              </select2>
            </div>
            <div class="detailsupplier" v-if="form.supplier_id != null">
              <div class="mb-8">
                <label class="form-label required"> Alamat : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-if="!!supplier_so" v-model="supplier_so.alamat" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPWP : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-if="!!supplier_so" :value="supplier_so.npwp || '-'" />
              </div>
              <div class="mb-8">
                <label class="form-label required"> NPPKP : </label>
                <input type="text" name="no_surat" id="no_surat"
                  class="form-control" autoComplete="off" disabled v-if="!!supplier_so" :value="supplier_so.nppkp || '-'" />
              </div>
            </div>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
        <div class="row">
          <div class="col-12">
            <div class="mb-8">
              <label class="form-label required"> No Surat : </label>
              <div class="input-group">
                <!-- <div class="input-group-prepend">
                  <span class="input-group-text" @change="nomor($event)">{{ nomor }}</span>
                </div> -->
                <input type="text" class="form-control" name="no_surat" required v-model="form.no_surat" placeholder="No Surat">
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Tanggal Permintaan : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_permintaan" id="tgl_permintaan" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_permintaan" />
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Bukti Permintaan : </label>
              <input type="text" name="bukti_permintaan" id="bukti_permintaan" placeholder="Bukti Permintaan"  @input.prevent="kd"
                class="form-control" autoComplete="off" v-model="form.bukti_permintaan" />
            </div>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
        <div class="row">
          <div class="col-12">
            <div class="mb-8">
              <label class="form-label required"> No Surat Pembelian : </label>
              <input name="no_surat_pembelian" id="no_surat_pembelian"  placeholder=" No Surat Pembelian"
                class="form-control" required autoComplete="off" v-model="form.no_surat_pembelian" />
            </div>
            <div class="mb-8">
              <label class="form-label required"> Diketahui Oleh : </label>
              <select2 name="diketahui_oleh" id="diketahui_oleh"
                class="form-control" autoComplete="off" v-model="form.diketahui_oleh" >
                <option value="" disabled>Pilih</option>
                <option v-for="user in users" :value="user.id" :key="user.uuid">{{ user.name }}</option>
              </select2>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Tanggal PO : </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text" style="padding-block: 1rem;"><i class="far fa-calendar-alt fa-1x"></i></span></div>              
                <datepicker name="tgl_po" id="tgl_po" placeholder="Pilih Tanggal"
                  class="form-control" required autoComplete="off" v-model="form.tgl_po" />
              </div>
            </div>
            <div class="mb-8">
              <label class="form-label required"> No PO Pembelian : </label>
              <input type="text" name="no_po_pembelian" id="no_po_pembelian" placeholder="No PO Pembelian"
                class="form-control" autoComplete="off" v-model="form.no_po_pembelian" />
            </div>
            <div class="mb-8">
              <label class="form-label required"> No Surat Jalan : </label>
              <input type="text" name="no_surat_jalan" id="no_surat_jalan" placeholder="No Surat Jalan"
                class="form-control" autoComplete="off" v-model="form.no_surat_jalan" />
            </div>
          </div>

          <div class="col-6">
            <div class="mb-8">
              <label class="form-label required"> Jenis Pembayaran : </label>
              <select2 name="jenis_pembayaran" id="jenis_pembayaran"
                class="form-control" required autoComplete="off" v-model="form.jenis_pembayaran" >
                <option value="Tunai">Tunai</option>
                <option value="Kredit">Kredit</option>
              </select2>
            </div>
            <div class="mb-8">
              <label class="form-label required"> Bank : </label>
              <select2 name="account_id" id="account_id"
                class="form-control" autoComplete="off" v-model="form.account_id" >
                <option disabled>Pilih</option>
                <option v-for="account in accounts" :value="account.id">{{ account.nm_account }}</option>
              </select2>
            </div>
            <div class="mb-8">
              <label class="form-label required"> Jatuh Tempo : </label>
              <div class="input-group">
                <input type="number" name="tempo" id="tempo" placeholder="jatuh Tempo"
                class="form-control" required autoComplete="off" v-model="form.tempo" />
                <div class="input-group-append"><span class="input-group-text">Hari</span></div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-auto mt-8 d-block">
              <i class="la la-arrow-circle-right"></i>
              Next
            </button>
          </div>
        </div>
        <hr style="margin-top: 2rem; margin-bottom: 3rem">
      </div>
    </form>

    <form v-if="!!selected" class="card mb-12" id="form-pembeliandetail" @submit.prevent="onSubmit" >
      <div class="detail">
        <div class="col">
          <button
            type="submit"
            class="btn btn-primary btn-sm me-auto mt-8 ms-4"
            @click.prevent="tambahPermintaanBarangJadi()"
          >
            <i class="la la-arrow-circle-right"></i>
            Tambah Barang Jadi
          </button>

          <button
            type="submit"
            class="btn btn-primary btn-sm me-auto mt-8 ms-4"
            @click.prevent="tambahPermintaanBarangMentah()"
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
                <th rowspan="2">Jumlah</th>
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
                    name="permintaan_id"
                    :id="'permintaan_id' + index"
                    class="form-control ms-2"
                    required
                    @change="getSatuanJadi(index, $event), getHargaBJ(index), getVolumeBJ(index), getJumlahBJ(index)" 
                    autoComplete="off"
                    v-model="item.permintaan_id"
                  >
                    <option disabled value="">Pilih barang jadi</option>
                    <option
                      v-for="item in permintaanbarangjadis"
                      :value="item.id"
                      :key="item.id"
                      :disabled="
                        form.barangjadi.findIndex(
                          (cat) => cat.permintaan_id == item.id
                        ) == -1
                          ? false
                          : true
                      "
                    >
                      {{ item.barang_jadi.nm_barang_jadi }}
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
                    
                    required
                  ></money3>
                </td>
                <td class="pe-6 ps-1">
                  <a href="javascript:void(0)">
                    <i
                      class="la la-trash icon-lg text-danger ms-5"
                      @click.prevent="hapusPermintaanBarangJadi(index)"
                      style="font-size: 22px"
                    ></i>
                  </a>
                </td>
              </tr>

              <tr v-for="(item, index) in form.barangmentah">
                <td>
                  <select2
                    name="permintaan_id"
                    :id="'permintaan_id' + index"
                    class="form-control ms-2"
                    required
                    @change="getSatuanMentah(index, $event), getHargaBM(index), getVolumeBM(index), getJumlahBM(index)"
                    autoComplete="off"
                    v-model="item.permintaan_id"
                  >
                    <option disabled value="">Pilih barang mentah</option>
                    <option
                      v-for="item in permintaanbarangmentahs"
                      :value="item.id"
                      :key="item.id"
                      :disabled="
                        form.barangmentah.findIndex(
                          (cat) => cat.permintaan_id == item.id
                        ) == -1
                          ? false
                          : true
                      "
                    >
                      {{ item.barang_mentah.nm_barangmentah }}
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
                    disabled
                    required
                  ></money3>
                </td>
                <td class="pe-6 ps-1">
                  <a href="javascript:void(0)" class="d-inline-block">
                    <i
                      @click.prevent="hapusPermintaanBarangMentah(index)"
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

      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-8">
              <label class="form-label"> Keterangan : </label>
              <textarea rows="10" name="keterangan" id="keterangan" placeholder="Keterangan"
                class="form-control" autoComplete="off" v-model="form.keterangan" >
              </textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="mt-8">
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label">Jumlah Penjualan</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <money3 type="text" name="jml_penjualan" id="jml_penjualan" v-bind="config"
                        class="form-control" required autoComplete="off" v-model="form.jml_penjualan" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row radio-inline">
                  <div class="col-md-2">
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon"
                        required autoComplete="off" value="persen" v-model="form.tipe_diskon" />
                        Persen (%)
                      </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_diskon" id="tipe_diskon"
                        required autoComplete="off" value="rupiah" v-model="form.tipe_diskon" />
                        Rupiah (Rp)
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label"  v-if="form.tipe_diskon != null">Diskon</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group" v-if="form.tipe_diskon=='persen'">
                      <input type="text" name="diskon" id="diskon"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                    <div class="input-group" v-if="form.tipe_diskon=='rupiah'">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <money3 type="text" name="diskon" id="diskon" v-bind="config"
                        class="form-control" required autoComplete="off" v-model="form.diskon" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label">Uang Muka</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <money3 type="text" name="uangmuka" id="uangmuka" v-bind="config"
                        class="form-control" required autoComplete="off" v-model="form.uangmuka" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row radio-inline">
                  <div class="col-md-2">
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_pajak" id="tipe_pajak"
                        required autoComplete="off" value="persen"  v-model="form.tipe_pajak" />
                        Persen (%)
                      </label>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <label class="form-label">
                        <input type="radio" name="tipe_pajak" id="tipe_pajak"
                        required autoComplete="off" value="rupiah" v-model="form.tipe_pajak" />
                        Rupiah (Rp)
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2" v-if="form.tipe_pajak != null">
                    <label class="form-label" style="font-size: small;">Dasar Pengenaan Pajak</label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group" v-if="form.tipe_pajak=='persen'">
                      <input type="text" name="pajak" id="pajak"
                        class="form-control" required autoComplete="off" v-model="form.pajak" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                    <div class="input-group" v-if="form.tipe_pajak=='rupiah'">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <input type="text" name="pajak" id="pajak"
                        class="form-control" required autoComplete="off" v-model="form.pajak" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label"> PPN : </label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <input type="text" name="ppn" id="ppn" placeholder="Pilih Tanggal"
                        class="form-control" required autoComplete="off" v-model="form.ppn" />
                      <div class="input-group-append"><span class="input-group-text">%</span></div> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-8">
                <div class="row">
                  <div class="col-md-2">
                    <label class="form-label"> Netto : </label>
                  </div>
                  <div class="col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">Rp</span></div> 
                      <money3 type="text" name="netto" id="netto"
                        class="form-control" v-bind="config" required autoComplete="off" v-model="form.netto" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-auto mt-8 d-block">
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
    props: {
      selected: {
        type: String,
        default: null,
      }
    },

    data() {
      return {
        nomor: null,

        profile_so: {},
        supplier_so: {},
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

    setup(props) {
      const queryClient = useQueryClient();
      const form = ref({
        details: [{}],
        barangmentah: [],
        barangjadi: []
      });
      const selected = ref(props.selected);

      const { data: pembelian = {details: [], barangjadi: [], barangmentah: []}, refetch } = useQuery(
        ["pembelian", selected, "edit"],
        () => {
          setTimeout(() => KTApp.block("#form-pembelian"), 10);
          return axios.get(`/pembelian/${selected.value}/edit`).then((res) => res.data);
        },
        {
          enabled: !!selected.value,
          cacheTime: 0,
          onSuccess: (data) => {
            KTApp.unblock('#form-pembelian')
            const datas = {...data};
              if (datas.barangjadi.length) {
                datas.barangjadi.push();
              }
              if (datas.barangmentah.length) {
                datas.barangmentah.push();
              }
              form.value = datas;
            },
          onError: error => console.log(error),
        }
      );
  
      const { mutate: submit } = useMutation((data) => axios.post(selected.value ? `/pembelian/${selected.value}/update` : '/pembelian/store', data).then(res => res.data), {
        onMutate: () => {
          KTApp.block("#form-pembelian");
        },
        onError: (error) => {
          toastr.error(error);
          KTApp.unblock("#form-pembelian");
        },
        onSettled: () => {
          KTApp.unblock("#form-pembelian");
        },
        onSuccess: (data) => {
          KTApp.unblock("#form-pembelian");
          const datas = {...data.data};
          // if (!datas.details.length) {
          //   datas.details.push({});
          // }
          selected.value = datas.uuid;
          console.log("oioioi");

          form.value = datas;
        }
      });

  
      const { data: profiles } = useQuery(["profiles"], () => 
        axios.get("/profile/get").then((res) => res.data)
      );
      const { data: suppliers } = useQuery(["suppliers"], () => 
        axios.get("/supplier/get").then((res) => res.data)
      );
      const { data: users } = useQuery(["users"], () => 
        axios.get("/user/get").then((res) => res.data)
      );
      const { data: accounts } = useQuery(["accounts"], () => 
        axios.get("/account/getdata").then((res) => res.data)
      );
      const { data: barangjadis = [] } = useQuery(["barang_jadis"], () =>
        axios.get("/barangjadi/get").then((res) => res.data)
      );
      const { data: barangmentahs = [] } = useQuery(["barang_mentahs"], () =>
        axios.get("/barangmentah/get").then((res) => res.data)
      );
      const { data: permintaanbarangjadis = [] } = useQuery(["permintaan_barang_jadi"], () =>
        axios.get("/permintaan/getBJ").then((res) => res.data)
      );

      const { data: permintaanbarangmentahs = [] } = useQuery(["permintaan_barang_mentah"], () =>
        axios.get("/permintaan/getBM").then((res) => res.data)
      );



      return {
        pembelian,
        profiles,
        accounts,
        suppliers,
        users,
        barangjadis,
        barangmentahs,
        permintaanbarangjadis,
        permintaanbarangmentahs,
        submit,
        form,
        queryClient,
        selected,
        refetch,
      }
    },
    methods: {

      tambahPermintaanBarangJadi() {
  
        this.form.barangjadi.push({});
      },
      tambahPermintaanBarangMentah() {

        this.form.barangmentah.push({});
      },

      hapusPermintaanBarangJadi(index) {
        this.form.barangjadi.splice(index, 1);
      },

      hapusPermintaanBarangMentah(index) {
        this.form.barangmentah.splice(index, 1);
      },

      getSatuanMentah(index, satuan_id) {
        setTimeout(() => {
          var app = this;

          satuan_id = app.permintaanbarangmentahs[index].barang_mentah.barangsatuan_id;

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

          satuan_id = app.permintaanbarangjadis[i]?.barang_jadi.barangsatuanjadi_id;

          var app = this;
          axios.get(`barangsatuanjadi/${satuan_id}/child`).then((res) => {
            app.form.barangjadi[index].satuanjadi = res.data.data;
          });
        }, 100);
      },

      getHargaBJ(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangjadi[index].harga = app.permintaanbarangjadis[index].harga;
        }, 100);
      },

      getHargaBM(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangmentah[index].harga = app.permintaanbarangmentahs[index].harga;
        }, 100);
      },
      
      getVolumeBJ(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangjadi[index].volume = app.permintaanbarangjadis[index].volume;
        }, 100);
      },

      getVolumeBM(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangmentah[index].volume = app.permintaanbarangmentahs[index].volume;
        }, 100);
      },
      
      getJumlahBJ(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangjadi[index].jumlah = app.permintaanbarangjadis[index].jumlah;
        }, 100);
      },

      getJumlahBM(index) {
        setTimeout(() => {
          var app = this;

          app.form.barangmentah[index].jumlah = app.permintaanbarangmentahs[index].jumlah;
        }, 100);
      },

      getTotal() {
        var app = this;
        var jumlah = 0;

        if (app.form.jenis_pembayaran == "Free") {
          app.form.total = 0;
        } else {
          for (let i = 0; i < app.form.barangmentah.length; i++) {
            jumlah += app.form.barangmentah[i].jumlah;
          }

          for (let i = 0; i < app.form.barangjadi.length; i++) {
            jumlah += app.form.barangjadi[i].jumlah;
          }

          // console.log(jumlah);
          app.form.total = jumlah;
        }
      },

      
      perusahaan(e){
        var app = this;
        if (!app.profiles) return;

        var index = app.profiles.findIndex((cat) => cat.id == e);

        app.profile_so = app.profiles[index];
        
        if (app.profile_so.npwp  == null) {
          app.profile_so.npwp = '-';
        }
        

      },

      supplier(e){
        var app = this;
      if (!app.suppliers) return;

      var index = app.suppliers.findIndex((cat) => cat.id == e);

      var data = app.suppliers[index];

      if (data.nppkp == null) {
        data.nppkp = "-";
      }

      if (data.npwp == null) {
        data.npwp = "-";
      }
      app.supplier_so = data;

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

        // const data = new FormData(document.getElementById("form-pembelian"));
        this.submit(vm.form, {
          onSuccess: (data) => {
            toastr.success(data.message);
            // vm.selected = data.data.uuid;
            vm.$parent.openForm = true;
            vm.$parent.selected = undefined;
            vm.refetch();
            vm.queryClient.invalidateQueries(["/pembelian/paginate"], { exact: true });
          }
        });
      },
      
      kd: _.debounce(function () {
      var vm = this;
      var myString = this.form.bukti_permintaan;
      var splits = myString.split(" ");
      var codes = "";
      for (var i = 0; i < splits.length; i++) {
        if (splits[i][0]) {
          codes = codes + splits[i][0];
        }
      }
      if (this.type == "store") {
        if (codes != "") {
          vm.kode =
            vm.nomor + "/SP/VRA-" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "/" + vm.bulan + "/" + vm.tahun;
          vm.form.no_surat = vm.kode;
          $(".codes").val(vm.kode);
        } else {
          vm.kode = "";
          vm.form.no_surat = vm.kode;
          $(".codes").val(vm.kode);
        }
      } else {
        if (codes != "") {
          vm.kode =
            vm.nomor + "/SP/VRA-" + codes.replace(/[^A-Za-z]/g, "").toUpperCase() + "/" + vm.bulan + "/" + vm.tahun;
          vm.form.no_surat = vm.kode;
            $(".codes").val(vm.kode);
          } else {
            vm.kode = "";
            vm.form.no_surat = vm.kode;
            $(".codes").val(vm.kode);
          }
        }
      }, 1000),

      gettahun() {
        this.$http
          .get("pembelian/gettahun")
          .then((res) => {
            console.log(res.data)
            this.tahun = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },

      getbulan() {
        this.$http
          .get("pembelian/getbulan")
          .then((res) => {
            console.log(res.data)
            this.bulan = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      
      getnomor() {
        this.$http
          .get("pembelian/getnomor")
          .then((res) => {
            console.log(res.data)
            this.nomor = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },

      editGetnomor() {
        this.$http
          .get("pembelian/" + this.selected + "/getnomor")
          .then((res) => {
            console.log(res.data);
            this.nomor = res.data;
          })
          .catch((err) => {
            console.log(err);
          });
      },
    },
    mounted() {
      if (!this.selected) {
        this.getnomor();
        this.getbulan();
        this.gettahun();
        return;
      }
      this.editGetnomor();
    },
  };
  </script>
  
  <style scoped> 
  .pojok {
    margin-left: 540px;
  }
  .table {
    vertical-align: middle;
    text-align: center;
  }

  </style>