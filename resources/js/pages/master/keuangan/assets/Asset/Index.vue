<template>
  <section>
    <Form v-if="openForm" :selected="selected" />
    <div class="card" v-if="!openAsset">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Assets</h1>
          <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto me-5" @click="openDetail = true" >
              <i class="las la-eye" style="margin-left: 0;"></i>
              {{($parent.selected)}}</button>
          <button
            type="button"
            class="btn btn-light-danger btn-sm"
            @click="($parent.openAsset = false), ($parent.selected = undefined)"
          >
            <i class="las la-times-circle"></i>
            Kembali
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-jenisasset"
          url="/asset/paginatejenisasset"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { useQueryClient } from "vue-query";
import { createColumnHelper } from "@tanstack/vue-table";
const columnHelper = createColumnHelper();

import Form from "./Form/Index.vue";
import { useDelete } from "@/libs/hooks";

export default {
  components: {
    Form,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openForm = ref(false);
    const openAsset = ref(false);

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nama", {
        header: "Jenis Asset",
        cell: (cell) => cell.getValue(),
      }),

      columnHelper.accessor("id", {
        header: "Aksi",
        cell: (cell) =>
          openForm.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-clean btn-icon ",
                    onClick: () => {
                      selected.value = cell.getValue();
                      openForm.value = true;
                      return;
                    },
                  },
                  h("i", { class: "la la-plus fs-2x kt-font-success" })
                ),
              ]),
      }),
    ];

    return {
      selected,
      openForm,
      openAsset,
      columns,
    };
  },
};
</script>

<style>
.kt-font-success {
  color: #42ba96;
}
</style>
