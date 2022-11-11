<template>
  <section>
    <Form v-if="openForm" :selected="selected" />
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Barang Mentah</h1>
          <button
            v-if="!openForm"
            type="button"
            class="btn btn-primary btn-sm ms-auto"
            @click="openForm = true"
          >
            <i class="las la-plus"></i>
            Barang Mentah Baru
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-barangmentah"
          url="/barangmentah/paginate"
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

import Form from "./Form.vue";
import { useDelete } from "@/libs/hooks";

export default {
  components: {
    Form,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openForm = ref(false);

    const { delete: deleteBarangMentah } = useDelete({
      onSuccess: () => {
        queryClient.invalidateQueries(["/barangmentah/paginate"]);
      },
    });

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_barangmentah", {
        header: "Nama Barang Mentah",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("kd_barang_mentah", {
        header: "Kode",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("stok", {
        header: "Stok Barang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_gudang", {
        header: "Gudang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("kategoribadges", {
        header: "Kategori Barang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
          openForm.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-warning",
                    onClick: () => {
                      KTUtil.scrollTop();
                      selected.value = cell.getValue();
                      openForm.value = true;
                    },
                  },
                  h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () => {
                      deleteBarangMentah(
                        `/barangmentah/${cell.getValue()}/destroy`
                      );
                    },
                  },
                  h("i", { class: "la la-trash fs-2" })
                ),
              ]),
      }),
    ];

    return {
      selected,
      openForm,
      columns,
    };
  },
};
</script>

<style></style>
