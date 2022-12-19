<template>
  <form class="card mb-12" id="form-profile" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            profile?.uuid ? `Edit Profile : ${profile.nama}` : "Tambah Profile"
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
      <br />
      <div class="row">
        <div class="col-3">
          <label for="name" class="form-label required"> Logo : </label>
          <file-upload
            :files="selected && form?.logo ? `/${form.logo}` : fileLogo"
            :allow-multiple="false"
            v-on:updatefiles="onUpdateFilesLogo"
            labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
            required
            :accepted-file-types="['image/*']"
          ></file-upload>
        </div>
        <div class="col-6">
          <label for="name" class="form-label required"> Kop : </label>
          <file-upload
            :files="selected && form?.kop ? `/${form.kop}` : fileKop"
            :allow-multiple="false"
            v-on:updatefiles="onUpdateFilesKop"
            labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
            required
            :accepted-file-types="['image/*']"
          ></file-upload>
        </div>
        <div class="col-3">
          <label for="name" class="form-label required"> Tanda tangan : </label>
          <file-upload
            :files="selected && form?.ttd ? `/${form.ttd}` : fileTtd"
            :allow-multiple="false"
            v-on:updatefiles="onUpdateFilesTtd"
            labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
            required
            :accepted-file-types="['image/*']"
          ></file-upload>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="mb-8">
            <label for="name" class="form-label required"> Nama : </label>
            <input
              type="text"
              name="nama"
              id="nama"
              placeholder="Nama "
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nama"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required"> Telepon : </label>
            <input
              type="text"
              name="telepon"
              id="telepon"
              placeholder="Telepon  profile"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.telepon"
            />
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required"> NPWP : </label>
            <input
              type="text"
              name="npwp"
              id="npwp"
              placeholder="NPWP"
              class="form-control"
              autoComplete="off"
              oninput="this.value = this.value.replace(/\D/g, '')"
              v-mask="'99.999.999.9-999.999'"
              v-model="form.npwp"
            />
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="code" class="form-label required"> FAX : </label>
            <input
              type="text"
              name="fax"
              id="fax"
              placeholder="fax"
              class="form-control"
              autoComplete="off"
              v-model="form.fax"
            />
          </div>
          <div class="mb-8">
            <label for="code" class="form-label required"> Pimpinan : </label>
            <input
              type="text"
              name="pimpinan"
              id="pimpinan"
              placeholder="pimpinan"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.pimpinan"
            />
          </div>
        </div>
      </div>
      <hr style="margin-top: 2rem; margin-bottom: 3rem" />
      <div class="row">
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Provinsi: </label>
            <select2
              name="provinsi_id"
              id="provinsi_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.provinsi_id"
            >
              <option
                v-for="provinsi in provinsis"
                :value="provinsi.id"
                :key="provinsi.uuid"
              >
                {{ provinsi.nm_provinsi }}
              </option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="name" class="form-label required">
              Kota/Kabupaten:
            </label>
            <select2
              name="kab_kota_id"
              id="kab_kota_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.kab_kota_id"
            >
              <option v-for="kota in kotas" :value="kota.id" :key="kota.uuid">
                {{ kota.nm_kab_kota }}
              </option>
            </select2>
          </div>
        </div>
        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Kecamatan: </label>
            <select2
              name="kecamatan_id"
              id="kecamatan_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.kecamatan_id"
            >
              <option
                v-for="kecamatan in kecamatans"
                :value="kecamatan.id"
                :key="kecamatan.uuid"
              >
                {{ kecamatan.nm_kecamatan }}
              </option>
            </select2>
          </div>
          <div class="mb-8">
            <label for="name" class="form-label required"> Kelurahan: </label>
            <select2
              name="kelurahan_id"
              id="kelurahan_id"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.kelurahan_id"
            >
              <option
                v-for="kelurahan in kelurahans"
                :value="kelurahan.id"
                :key="kelurahan.uuid"
              >
                {{ kelurahan.nm_kelurahan }}
              </option>
            </select2>
          </div>
        </div>
        <div class="col-12">
          <div class="mb-8">
            <label for="code" class="form-label required"> Alamat : </label>
            <textarea
              name="alamat"
              id="alamat"
              placeholder="Alamat"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.alamat"
            />
          </div>
          <div class="mb-8"></div>
        </div>
        <div class="col-12">
          <button
            type="submit"
            class="btn btn-primary btn-sm ms-auto mt-8 d-block"
          >
            <i class="las la-save"></i>
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

export default {
  props: {
    selected: {
      type: String,
      default: null,
    },
  },
  setup({ selected }) {
    const queryClient = useQueryClient();
    const form = ref({});

    const fileLogo = ref([]);
    const fileKop = ref([]);
    const fileTtd = ref([]);

    const { data: profile } = useQuery(
      ["profile", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-profile"), 100);
        return axios.get(`/profile/${selected}/edit`).then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-profile"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/profile/${selected}/update` : "/profile/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-profile");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-profile");
        },
      }
    );

    const { data: provinsis } = useQuery(
      ["provinsis"],
      () => axios.get("/provinsi/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );
    const { data: kotas } = useQuery(
      ["kotas"],
      () => axios.get("/kota/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );
    const { data: kecamatans } = useQuery(
      ["kecamatans"],
      () => axios.get("/kecamatan/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );
    const { data: kelurahans } = useQuery(
      ["kelurahans"],
      () => axios.get("/kelurahan/get").then((res) => res.data),
      {
        placeholderData: [],
      }
    );

    return {
      profile,
      fileLogo,
      fileKop,
      fileTtd,
      submit,
      form,
      provinsis,
      kotas,
      kecamatans,
      kelurahans,
      queryClient,
    };
  },
  methods: {
    onUpdateFilesLogo(filesLogo) {
      this.fileLogo = filesLogo;
    },
    onUpdateFilesKop(filesKop) {
      this.fileKop = filesKop;
    },
    onUpdateFilesTtd(filesTtd) {
      this.fileTtd = filesTtd;
    },

    // methods: {
    //   onUpdateFilesLogo(files) {
    //     this.fileLogo = files;
    //   },
    //   onUpdateFilesKop(files) {
    //     this.fileKop = files;
    //   },
    //   onUpdateFilesTtd(files) {
    //     this.fileTtd = files;
    //   },

    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-profile"));
      data.append("logo", this.fileLogo[0].file);
      data.append("kop", this.fileKop[0].file);
      data.append("ttd", this.fileTtd[0].file);
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/profile/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
