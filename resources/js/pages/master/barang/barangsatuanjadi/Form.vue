<template>
  <form
    class="card mb-12"
    id="form-barangsatuanjadi"
    @submit.prevent="onSubmit"
  >
    <div class="card-header">
      <div class="card-title w-100">
        <h3>
          {{
            barangsatuanjadi?.uuid
              ? `Edit Satuan Jadi : ${barangsatuanjadi.nm_satuan_jadi}`
              : "Tambah Satuan Jadi"
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
              Satuan Jadi :
            </label>
            <input
              type="text"
              name="nm_satuan_jadi"
              id="name"
              placeholder="Satuan Jadi"
              class="form-control"
              required
              autoComplete="off"
              v-model="form.nm_satuan_jadi"
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
                  <th>
                    <a href="javascript:void(0)">
                      <i
                        @click.prevent="plus()"
                        class="la la-plus icon-lg text-success mr-5"
                        style="font-size: 25px"
                      ></i>
                    </a>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in form.child">
                  <td>
                    <input
                      type="text"
                      name="nm_satuan_jadi_children"
                      id="name"
                      placeholder="Nama Satuan Children"
                      class="form-control"
                      requiredautoComplete="off"
                      v-model="item.nm_satuan_jadi_children"
                    />
                  </td>
                  <td>
                    <input
                      type="number"
                      name="nilai"
                      id="name"
                      placeholder="Nilai Satuan (buah)"
                      class="form-control"
                      requiredautoComplete="off"
                      v-model="item.nilai"
                    />
                  </td>
                  <td>
                    <a href="javascript:void(0)">
                      <i
                        @click.prevent="minus(index)"
                        class="la la-minus icon-lg text-danger mr-5"
                        style="font-size: 25px"
                      ></i>
                    </a>
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
        {
          nm_satuan_jadi_children: "Buah",
          nilai: 1,
        },
      ],
    });

    const { data: barangsatuanjadi } = useQuery(
      ["barangsatuanjadi", selected, "edit"],
      () => {
        setTimeout(() => KTApp.block("#form-barangsatuanjadi"), 100);
        return axios
          .get(`/barangsatuanjadi/${selected}/edit`)
          .then((res) => res.data);
      },
      {
        enabled: !!selected,
        cacheTime: 0,
        onSuccess: (data) => (form.value = data),
        onSettled: () => KTApp.unblock("#form-barangsatuanjadi"),
      }
    );

    const { mutate: submit } = useMutation(
      (data) =>
        axios
          .post(
            selected
              ? `/barangsatuanjadi/${selected}/update`
              : "/barangsatuanjadi/store",
            data
          )
          .then((res) => res.data),
      {
        onMutate: () => {
          KTApp.block("#form-barangsatuanjadi");
        },
        onError: (error) => {
          toastr.error(error.response.data.message);
        },
        onSettled: () => {
          KTApp.unblock("#form-barangsatuanjadi");
        },
      }
    );

    return {
      barangsatuanjadi,
      submit,
      form,
      queryClient,
    };
  },
  methods: {
    plus() {
      this.form.child.push({
        // barang_mentah_id: null,
        // stok_digunakan: null,
        // satuan_id: null,
      });
    },

    minus(index) {
      this.form.child.splice(index, 1);
    },
    onUpdateFiles(files) {
      this.file = files;
    },
    onSubmit() {
      const vm = this;
      vm.form.child;
      // const data = new FormData(
      //   document.getElementById("form-barangsatuanjadi")
      // );
      this.submit(vm.form, {
        onSuccess: (data) => {
          toastr.success(data.message);
          vm.$parent.openForm = false;
          vm.$parent.selected = undefined;
          vm.queryClient.invalidateQueries(["/barangsatuanjadi/paginate"], {
            exact: true,
          });
        },
      });
    },
  },
};
</script>

<style></style>
