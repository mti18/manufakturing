<template>
  <section>
    <Detail v-if="openDetail" :selected="selected"></Detail>
    <div class="card" v-if="!openDetail">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Konfirmasi Order</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-konfirmasiorder"
          url="/konfirmasiorder/paginate"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>

    <div class="card" style="margin-top: 3rem" v-if="!openDetail">
      <div class="card-body">
        <mti-paginate
          id="table-konfirmasiorderdone"
          url="/konfirmasiorder/paginate"
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

import Detail from "./Detail.vue";

export default {
  components: {
    Detail,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openDetail = ref(false);

    const columns = [
      columnHelper.accessor("no_pemesanan", {
        header: "No Pemesanan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("profile.nama", {
        header: "Nama Perusahaan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("supplier.nama", {
        header: "Nama Pemesan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("user.name", {
        header: "User",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
          h("div", { class: "d-flex gap-2" }, [
            h(
              "button",
              {
                class: "btn btn-sm btn-icon btn-success",
              },
              h("i", { class: "la fa-file-pdf fs-2" })
            ),
            h(
              "button",
              {
                class: "btn btn-sm btn-icon btn-primary",
                onClick: () => {
                  selected.value = cell.getValue();
                  openDetail.value = true;
                  return;
                },
              },
              h("i", { class: "la la-eye fs-2" })
            ),
          ]),
      }),
    ];

    return {
      selected,
      openDetail,
      columns,
    };
  },
};
</script>

<style></style>
