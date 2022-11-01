<template>
  <form class="card mb-12" id="form-satuan" @submit.prevent="onSubmit">
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            barangsatuans?.uuid
              ? `Edit Satuan : ${barangsatuans.nm_satuan}`
              : "Tambah Satuan"
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
              Nama Satuan :
            </label>
            <input
              type="text"
              name="nm_satuan"
              id="name"
              placeholder="Padat / Cair ..."
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nm_satuan"
            />
          </div>
        </div>

        <div class="col-6">
          <div class="mb-8">
            <label for="name" class="form-label required"> Satuan : </label>
            <input
              type="text"
              name="satuan"
              id="name"
              placeholder="m / l / g"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.satuan"
              @input.prevent="changeSatuan($event)"
            />
          </div>
        </div>
        <div class="col-12">
          <div class="table-responsive">
            <table class="table gs-7 gy-7 gx-7">
              <thead>
                <tr
                  class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200"
                >
                  <th>Name</th>
                  <th>Nilai</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in form.child">
                  <td>
                    <input
                      type="text"
                      class="form-control form-control-solid"
                      readonly
                      v-model="item.nm_satuan_children"
                      :placeholder="item.nm_satuan_children"
                    />
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control form-control-solid"
                      readonly
                      v-model="item.nilai"
                      :placeholder="item.nilai"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
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
      child: [
        { nm_satuan_children: "k", nilai: 1000000 },
        { nm_satuan_children: "h", nilai: 100000 },
        { nm_satuan_children: "da", nilai: 10000 },
        { nm_satuan_children: "", nilai: 1000 },
        { nm_satuan_children: "d", nilai: 100 },
        { nm_satuan_children: "c", nilai: 10 },
        { nm_satuan_children: "m", nilai: 1 },
      ],
    });

    const { data: barangsatuans } = useQuery(
      ["barangsatuan", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-satuan"), 100);
        return axios
          .get(`/barangsatuan/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-satuan"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected
              ? `/barangsatuan/${selected}/update`
              : "/barangsatuan/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-satuan");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-satuan");
        },
      }
    );

    return {
      barangsatuans,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    createChild() {
      var app = this;
      app.form.child = [
        { nm_satuan_children: "k", nilai: 1000000 },
        { nm_satuan_children: "h", nilai: 100000 },
        { nm_satuan_children: "da", nilai: 10000 },
        { nm_satuan_children: "", nilai: 1000 },
        { nm_satuan_children: "d", nilai: 100 },
        { nm_satuan_children: "c", nilai: 10 },
        { nm_satuan_children: "m", nilai: 1 },
      ];
    },
    changeSatuan(e) {
      var app = this;

      app.createChild();

      var oi = app.form.child.map(gabung);
      function gabung(item) {
        return {
          nm_satuan_children: item.nm_satuan_children + e.target.value,
          nilai: item.nilai,
        };
      }

      app.form.child = oi;
    },

    onUpdateFiles(files) {
      this.file = files;
      
      
    },
    onSubmit() {
      const vm = this;
      var data = vm.form;

      console.log(data);
      this.submit(data, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/barangsatuan/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
