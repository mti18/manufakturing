<template>
    <div class="card card-custom mb-4 card-create">
      <div class="card-body">
        <form @submit.prevent="getDataMonth" class="row">
          <div class="form-group col-md-4">
            <label>Bulan</label>
            <select2 class="form-control" v-model="$parent.formRequest.bulan">
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
            <select2 class="form-control" v-model="$parent.formRequest.tahun">
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
          <label>Type</label>
          <select2
            class="form-control"
            v-model="$parent.formRequest.type"
          >
            <option value="0">Umum</option>
            <option value="1">Penyesuaian</option>
            <option value="2">penutup</option>
          </select2>
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
      account: [],
      formRequest: {
        bulan: "",
        tahun: "",
        type: "",
      },
    };
  },
  methods: {
    sendFilter() {
      var app = this;
      if (app.$parent.formRequest.bulan == "" || app.$parent.formRequest.tahun == "" ||  app.$parent.formRequest.type == "") {
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
  
  },
  mounted() {
    
  },
};
</script>
