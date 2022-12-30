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
            <option value="" disabled selected>Tahun</option>
            <option value="0">Belum disesuaikan</option>
            <option value="1">Sudah disesuaikan</option>
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
          app.url =
          "/neraca/neraca/" +
          app.$parent.formRequest.bulan +
          "/" +
          app.$parent.formRequest.tahun +
          "/" +
          app.$parent.formRequest.type;

        app.axios
          .get(app.url)
          .then(function (response) {
            app.$parent.data = response.data;
            app.$parent.debit = 0;
            app.$parent.kredit = 0;

            for (let index = 0; index < response.data.length; index++) {
              const element = response.data[index];

              if (element.sum > 0) {
                app.debit += element.sum;
              } else {
                app.kredit += Math.abs(element.sum);
              }
            }

            app.$parent.showneraca = true;
            app.$parent.openFilters = false;
          })
          .catch(function (error) {
            console.log(error);
          });
      }
      },
    
    },
    mounted() {
      
    },
  };
  </script>
  