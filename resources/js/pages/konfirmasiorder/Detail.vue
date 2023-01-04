<template>
  <section>
    <div class="card" v-if="!openDetail">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Detail Order</h1>
          <button
            type="button"
            class="btn btn-light-danger btn-sm ms-auto"
            @click="
              ($parent.openDetail = false), ($parent.selected = undefined)
            "
          >
            <i class="las la-times-circle"></i>
            Kembali
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-detail"
          url="/salesorderdetail/paginate"
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

export default {
  components: {},
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openDetail = ref(false);

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) =>
          h("div", {}, [
            h("input", {
              class: "form-check-input mt-2",
              type: "checkbox",
              value: "",
              id: "flexCheckDefault",
            }),
          ]),
      }),
      columnHelper.accessor("nomor", {
        header: "Nomor Pemesanan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nm_satuan", {
        header: "Nama Barang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("volume", {
        header: "Volume",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("harga", {
        header: "Harga",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("jumlah", {
        header: "Jumlah",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) =>
          cell.getValue() == "0"
            ? h("div", { class: "d-flex gap-2" }, [
                h("span", { class: "badge badge-warning" }, "Pending"),
              ])
            : h("div", { class: "d-flex gap-2 ms-4" }, [
                h("span", { class: "success" }, "Accept"),
              ]),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
          h("div", { class: "d-flex gap-2" }, [
            h(
              "button",
              {
                class: "btn btn-sm btn-icon",
              },
              h("i", { class: "la la-check fs-2 text-success" })
            ),
            h(
              "button",
              {
                class: "btn btn-sm btn-icon",
              },
              h("i", { class: "la la-close fs-2 text-danger" })
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

  method: {},
};
</script>

<style scoped></style>
