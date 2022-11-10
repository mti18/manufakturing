<template>
  <form class="card mb-12" id="form-barangjadi" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            barangjadi?.uuid
              ? `Edit Barang Jadi : ${barangjadi.nm_barang_jadi}`
              : "Tambah Barang Jadi"
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
            <label for="name" class="form-label required">
              Barang Jadi :
            </label>
            <input
              type="text"
              name="nm_barang_jadi"
              id="name"
              placeholder="Barang Jadi"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nm_barang_jadi"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Stok : </label>
            <input
              type="number"
              name="stok"
              id="name"
              placeholder="Stok Barang"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.stok"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="nm_satuan_jadi" class="form-label required">
              Satuan Jadi :
            </label>
            <select2
              class="form-control"
              name="barangsatuanjadi_id"
              placeholder="Pilih Nama Satuan Jadi"
              id="barangsatuanjadi_id"
              v-model="form.barangsatuanjadi_id"
              required
            >
              <option value="" disabled>Pilih Satuan</option>
              <option
                v-for="item in barangsatuanjadi"
                :value="item.id"
                :key="item.id"
              >
                {{ item.nm_satuan_jadi }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="nm_gudang" class="form-label required">
              Gudang :
            </label>
            <select2
              class="form-control"
              name="gudang_id"
              placeholder="Pilih Gudang"
              id="gudang_id"
              v-model="form.gudang_id"
              required
            >
              <option value="" disabled>Pilih Gudang</option>
              <option v-for="item in gudang" :value="item.id" :key="item.id">
                {{ item.nm_gudang }}
              </option>
            </select2>
          </div>
        </div>

        <div class="col-md-6 form-group">
          <label class="required form-label">Kategori</label>
          <div
            class="form-check mb-2 form-check-custom form-check-solid form-check-sm"
            v-for="item in kategoris"
            :value="item.id"
          >
            <input
              class="form-check-input"
              type="checkbox"
              :value="item.id"
              id="flexRadioLg"
              @change="addkategori(item)"
              :checked="
                form.barangjadikategoris?.findIndex(
                  (cat) => cat.id == item.id
                ) != -1
                  ? true
                  : false
              "
            />
            <label class="form-check-label" for="flexRadioLg">
              {{ item.nm_kategori }}
            </label>
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label>Harga Barang</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
              </div>
              <input
                type="text"
                class="form-control"
                required
                name="harga"
                placeholder="Harga Barang"
              />
              <!-- oninput="this.value = this.value.rupiah(true)" -->
              <!-- v-model="formData.harga" -->
            </div>
          </div>
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
    const form = ref({
      barangjadikategoris: [],
    });

    const { data: barangsatuanjadi } = useQuery(["barang_satuan_jadis"], () =>
      axios.get("/barangsatuanjadi/get").then((res) => res.data)
    );

    const { data: gudang } = useQuery(["gudangs"], () =>
      axios.get("/gudang/get").then((res) => res.data)
    );

    const { data: kategoris = [] } = useQuery(["kategoris"], () =>
      axios.get("/kategori/get").then((res) => res.data)
    );

    const { data: barangjadi } = useQuery(
      ["barangjadi", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-barangjadi"), 100);
        return axios
          .get(`/barangjadi/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-barangjadi"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected ? `/barangjadi/${selected}/update` : "/barangjadi/store",
            form._value
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-barangjadi");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-barangjadi");
        },
      }
    );

    return {
      barangjadi,
      barangsatuanjadi,
      kategoris,
      gudang,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    addkategori(item) {
      var app = this;

      // console.log(app.form.kategoris);
      // return;

      var index = app.form.barangjadikategoris.findIndex(
        (cat) => cat.id == item.id
      );

      if (index == -1) {
        app.form.barangjadikategoris.push(item);
      } else {
        app.form.barangjadikategoris.splice(index, 1);
      }
    },

    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      const data = new FormData(document.getElementById("form-barangjadi"));
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/barangjadi/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
