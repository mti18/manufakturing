<template>
  <div class="card card-custom mb-4 card-create">
    <div class="card-body">
      <form @submit.prevent="getDataMonth" >
        <div class="row mb-2">
          <div class="form-group col-md-6">
            <label>Nama Perusahaan</label>
            <select2 class="form-control mt-2" 
              name="profile_id"
              id="profile"
              v-model="$parent.formRequest.profile_id">
              <option value="" disabled selected>Profile</option>
              <option
              v-for="profile in profile"
              :value="profile.id"
              :key="profile.uuid"
            >
              {{ profile.nama  }}
            </option>
          </select2>
        </div>
        <div class="form-group col-md-6">
          <label>Nama Supplier</label>
          <select2 class="form-control mt-2" v-model="$parent.formRequest.supplier_id">
            <option value="" disabled selected>Supplier</option>
            <option
            v-for="supplier in supplier"
            :value="supplier.id"
            :key="supplier.uuid"
            >
              {{ supplier.nama }}
            </option>
          </select2>
        </div>
      </div>
      <div class="row ">
        <div class="form-group col-md-4">
          <label>Bulan</label>
          <select2 class="form-control mt-2" v-model="$parent.formRequest.bulan">
            <option value="" disabled selected>Bulan</option>
            <option
              v-for="bulan in bulans"
              :value="bulan.id"
              :data="bulan"
              :key="bulan.uuid"
              >
              {{ bulan.name }}
            </option>
          </select2>
        </div>
        <div class="form-group col-md-4">
          <label>Tahun</label>
          <select2 class="form-control mt-2" v-model="$parent.formRequest.tahun">
            <option value="" disabled selected>Tahun</option>
            <option
              v-for="tahun in tahuns"
              :value="tahun.id"
              :data="tahun"
              :key="tahun.uuid">
              {{ tahun }}
            </option>
          </select2>
        </div>
        <div class="form-group col-md-4">
        <label>Type Pembayaran</label>
        <select2
        class="form-control mt-2"
        v-model="$parent.formRequest.type_pem"
        >
        <option value="0">ALL</option>
        <option value="1">Debit</option>
        <option value="2">Kredit</option>
      </select2>
    </div>
    </div>
    <div class="row mt-10">
      <div class="d-grid gap-2">
        <button
        id="btnSearch"
        @click="sendFilter"
        button
        type="button"
              class="btn btn-success btn-sm ms-2"
            >
              <i class="las la-save"></i> Filter
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
// AUTO GENERATE VUE FILE BY MCFLYON ARTISAN COMMAND
import { ref } from "vue";
  import { useQuery, useMutation } from "vue-query";
  import axios from "@/libs/axios";
  import { useQueryClient } from "vue-query";
  
export default {
data() {
  return {
    data: [],
    bulans: [
      { id: 1, name: "Januari" },
      { id: 2, name: "Februari" },
      { id: 3, name: "Maret" },
      { id: 4, name: "April" },
      { id: 5, name: "Mei" },
      { id: 6, name: "Juni" },
      { id: 7, name: "Juli" },
      { id: 8, name: "Agustus" },
      { id: 9, name: "September" },
      { id: 10, name: "Oktober" },
      { id: 11, name: "November" },
      { id: 12, name: "Desember" },
    ],
    url: "",
    tahuns: _.range(new Date().getFullYear(), 1999),
    profile: [],
    supplier: [],
    formRequest: {
      bulan: "",
      tahun: "",
      type_pem: "",
    },
  };
},
setup() {
    const queryClient = useQueryClient();
      const selected = ref();
      const openFilters = ref(true);

      const { data: supplier } = useQuery(["suppliers"], () =>
      axios.get("/supplier/show").then((res) => res.data)
    );
      const { data: profile } = useQuery(["profiles"], () =>
      axios.get("/profile/show").then((res) => res.data)
    );
 
  
      return {
        queryClient,
        profile,
        supplier,
        selected,
        openFilters,
      
      }
    },
methods: {
  sendFilter() {
    var app = this;
    if (app.$parent.formRequest.bulan == "" || app.$parent.formRequest.tahun == "" ||  app.$parent.formRequest.type_pem == "") {
      app.$toast.error("Bulan , Tahun , dan Type harus diisi");
       return;
    } else {
      app.axios
        .get(
          "/jurnal/jurnal/" +
            app.$parent.formRequest.bulan +
            "/" +
            app.$parent.formRequest.tahun +
            "/" +
            app.$parent.formRequest.type
        )
        .then((res) => {
          app.$parent.masterjurnal = res.data;
          app.$parent.kredit = 0;
          app.$parent.debit = 0;

          res.data.forEach((element) => {
            element.jurnal_item.forEach((item) => {
              app.$parent.debit += item.debit;
              app.$parent.kredit += item.kredit;
            });
          });

          app.$parent.openFilters = false;
        })
        .catch((err) => {
          console.log(err);
        });
    }
  },
  getProfile() {
      setTimeout(() => {
        var app = this;
        var app = app.formRequest.profile_id;
        axios
          .get(`profile/show`)
          .then((res) => {
            app.profile= res.data;
          })
          .catch((err) => {
            toastr.error("sesuatu error terjadi", "gagal");
          });
      }, 500);
    },
    

},
  mounted() {
  // var app = this;
  // app.getProfile();
  
},
};
</script>
