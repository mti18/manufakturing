<template>
  <section id="main">
    <Form v-if="openForm" :selected="selected" />
    <Retur v-if="openRetur" :selected="selected" />
    <div class="card" v-if="!openRetur">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Sales Order</h1>
          <button
            v-if="!openForm"
            type="button"
            class="btn btn-primary btn-sm ms-auto"
            @click="openForm = true"
          >
            <i class="las la-plus"></i>
            Tambah Sales Order
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-salesorder"
          url="/salesorder/paginate"
          :columns="columnsSO"
        ></mti-paginate>
        <div class="collapse" id="detail">
          <div class="card card-body">
            <table>
              <tr v-for="item in detail">
                <td>Barang</td>
                <td>{{ item.volume }}</td>
                <td>Rp. {{ item.harga }}</td>
                <td>{{ item.diskon }}</td>
                <td>Rp. {{ item.jumlah }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card" v-else-if="!openForm">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Retur Barang</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-salesorder"
          url="/returbarang/paginate"
          :columns="columnsRB"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { useQueryClient } from "vue-query";
import { createColumnHelper } from "@tanstack/vue-table";
import axios from "@/libs/axios";

const columnHelper = createColumnHelper();

import Form from "./Form.vue";
import Retur from "./returbarang/Form.vue";
import { useDelete, useDownloadPdf } from "@/libs/hooks";

export default {
  components: {
    Form,
    Retur,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const detail = ref();
    const openForm = ref(false);
    const openRetur = ref(false);

    const { delete: deletesalesorder } = useDelete({
      onSuccess: () => {
        queryClient.invalidateQueries(["/salesorder/paginate"]);
      },
    });

    const { download: downloadPdf } = useDownloadPdf();

    const columnsSO = [
      columnHelper.accessor("no_pemesanan", {
        header: "NO",

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
      columnHelper.accessor("tempo", {
        header: "Jatuh Tempo",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("diketahuioleh.name", {
        header: "User",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("pembayaran", {
        header: "Pembayaran",
        cell: (cell) =>
          cell.getValue() == "yes"
            ? h("div", { class: "d-flex gap-2 ms-8" }, [
                h(
                  "span",
                  {
                    class: "badge badge-success",
                    style: "border-radius: 50px",
                  },
                  "Yes"
                ),
              ])
            : h("div", { class: "d-flex gap-2 ms-8" }, [
                h(
                  "span",
                  { class: "badge badge-danger", style: "border-radius: 50px" },
                  "No"
                ),
              ]),
      }),
      columnHelper.accessor("status", {
        header: "Status",
        cell: (cell) =>
          cell.getValue() == "draft"
            ? h(
                "span",
                { class: "badge badge-danger", style: "border-radius: 50px" },
                "Draft"
              )
            : cell.getValue() == "process"
            ? h(
                "span",
                { class: "badge badge-warning", style: "border-radius: 50px" },
                "Proses"
              )
            : cell.getValue() == "ready"
            ? h(
                "span",
                { class: "badge badge-info", style: "border-radius: 50px" },
                "Ready"
              )
            : h("span", { class: "badge badge-success" }, "Selesai"),
      }),

      columnHelper.accessor("tgl_pesan", {
        header: "Tanggal Pesan",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("tgl_pengiriman", {
        header: "Tanggal Pengiriman",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) => {
          if (openForm.value) return null;
          const status = cell.row.original.status;
          const pembayaran = cell.row.original.pembayaran;
          const id = cell.row.original.id;

          const buttons = [];
          if (status === "draft") {
            buttons.push(
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-primary",
                  onClick: () =>
                    downloadPdf(
                      `/salesorder/${cell.getValue()}/generatepdf1`,
                      "GET"
                    ),
                },
                h("i", { class: "la la-print text-success fs-2" })
              ),
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-secondary",
                  onClick: () =>
                    downloadPdf(
                      `/salesorder/${cell.getValue()}/generatepdf2`,
                      "GET"
                    ),
                },
                h("i", { class: "la la-print  fs-2" })
              ),

              h(
                "button",
                {
                  class:
                    "btn btn-sm btn-icon btn-active-light-secondary btn-eye",
                  type: "button",
                  "data-bs-toggle": "collapse",
                  "data-bs-target": "#detail",
                  "aria-expanded": "false",
                  "aria-controls": "detail",
                  onClick: () =>
                    axios.get(`salesorder/` + id + `/getDetail`).then((res) => {
                      console.log(res.data.data);

                      detail.value = res.data.data;
                    }),
                },
                h("i", {
                  class: "la la-eye text-primary rotate-90 fs-2",
                })
              ),

              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-warning",
                  onClick: () => {
                    KTUtil.scrollTop();
                    selected.value = cell.getValue();
                    openForm.value = true;
                  },
                },
                h("i", { class: "la la-pencil text-warning fs-2" })
              ),
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-danger",
                  onClick: () => {
                    deletesalesorder(`/salesorder/${cell.getValue()}/destroy`);
                  },
                },
                h("i", { class: "la la-trash text-danger fs-2" })
              ),
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-warning",
                  // onClick: () => {
                  //   deletesalesorder(
                  //     `/salesorder/${cell.getValue()}/destroy`
                  //   );
                  // },
                },
                h("i", { class: "la la-money-bill-wave text-warning fs-2" })
              ),
              h(
                "button",
                {
                  class: "btn btn-sm btn-icon btn-active-light-warning",
                  onClick: () => {
                    KTUtil.scrollTop();
                    selected.value = cell.getValue();
                    openRetur.value = true;
                  },
                },
                h("i", { class: "la la-backspace text-warning fs-2" })
              )
            );
          } else if (status === "process") {
            if (
              pembayaran ===
              "no" /*&&  $pembayaran->sum('bayar') == $salesOrder->netto*/
            ) {
            } else {
            }
          } else if (status === "ready") {
            if (pembayaran == "no") {
            } else {
            }
          } else {
            if (pembayaran == "no") {
            } else {
            }
          }

          return h("div", { class: "d-inlineblock gap-2" }, buttons);
          // h("div", { class: "d-inlineblock gap-2" }, [
          // ]);
        },
      }),
    ];

    const columnsRB = [
      columnHelper.accessor("no_pemesanan", {
        header: "No Pemesanan",

        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("no_pemesanan", {
        header: "Nama Barang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("no_pemesanan", {
        header: "Jumlah Retur",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("no_pemesanan", {
        header: "Status",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("no_pemesanan", {
        header: "Tanggal",
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
                      deleteKategori(`/kategori/${cell.getValue()}/destroy`);
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
      openRetur,
      columnsSO,
      detail,
      columnsRB,
    };
  },

  methods: {
    // getDetail() {
    //   setTimeout(() => {
    //     var app = this;
    //     var app = app.form.account_id;
    //     axios
    //       .get(`masterjurnal/child`)
    //       .then((res) => {
    //         app.child = res.data;
    //       })
    //       .catch((err) => {
    //         toastr.error("sesuatu error terjadi", "gagal");
    //       });
    //   }, 500);
    // },
  },
};
</script>

<style scoped>
table tr td {
  border: 1px solid rgb(200, 200, 200);
  padding: 10px;
}
</style>
