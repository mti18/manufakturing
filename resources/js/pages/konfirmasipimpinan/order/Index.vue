<template>
  <section>
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Konfirmasi Order</h1>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-orderprocess"
          url="konfirmasipimpinan/order/paginate/process"
          :columns="columnsProcess"
        ></mti-paginate>
      </div>
    </div>

    <div class="card" style="margin-top: 3rem">
      <div class="card-body">
        <mti-paginate
          id="table-ordersuccess"
          url="konfirmasipimpinan/order/paginate/success"
          :columns="columnsSuccess"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
import axios from "@/libs/axios";
const columnHelper = createColumnHelper();
import { useQueryClient } from "vue-query";

import { useDownloadPdf } from "@/libs/hooks";

export default {
  components: {},
  setup() {
    const selected = ref();
    const openForm = ref(false);
    const queryClient = useQueryClient();

    const { download: downloadPdf } = useDownloadPdf();

    const columnsProcess = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
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
          openForm.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    onClick: () =>
                      downloadPdf(
                        `/konfirmasipimpinan/order/${cell.getValue()}/generatepdforder`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () =>
                      downloadPdf(
                        `/salesorder/${cell.getValue()}/generatepdf1`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-secondary",
                    onClick: () =>
                      downloadPdf(
                        `/salesorder/${cell.getValue()}/generatepdf2`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-info",
                  },
                  h("i", { class: "la la-eye fs-2" })
                ),

                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    onClick: () =>
                      axios
                        .post(
                          `konfirmasipimpinan/order/${cell.getValue()}/accept`
                        )
                        .then((res) => {
                          toastr.success("Berhasil di Konfirmasi");
                          queryClient.invalidateQueries([
                            "konfirmasipimpinan/order/paginate/success",
                          ]);
                          queryClient.invalidateQueries([
                            "konfirmasipimpinan/order/paginate/process",
                          ]);
                        }),
                  },
                  h("i", { class: "la la-check fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-danger",
                    // onClick: () => {
                    //   KTUtil.scrollTop();
                    //   selected.value = cell.getValue();
                    //   openForm.value = true;
                    // },
                  },
                  h("i", { class: "la la-close fs-2" })
                ),
              ]),
      }),
    ];
    const columnsSuccess = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
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
          openForm.value
            ? null
            : h("div", { class: "d-flex gap-2" }, [
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    onClick: () =>
                      downloadPdf(
                        `/konfirmasipimpinan/order/${cell.getValue()}/generatepdforder`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () =>
                      downloadPdf(
                        `/salesorder/${cell.getValue()}/generatepdf1`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-secondary",
                    onClick: () =>
                      downloadPdf(
                        `/salesorder/${cell.getValue()}/generatepdf2`,
                        "GET"
                      ),
                  },
                  h("i", { class: "la la-file-pdf fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () =>
                      axios
                        .post(
                          `konfirmasipimpinan/order/${cell.getValue()}/revisi`
                        )
                        .then((res) => {
                          toastr.success("Berhasil di Revisi");
                          queryClient.invalidateQueries([
                            "konfirmasipimpinan/order/paginate/success",
                          ]);
                          queryClient.invalidateQueries([
                            "konfirmasipimpinan/order/paginate/process",
                          ]);
                        }),
                  },
                  h("i", { class: "la la-close fs-2" })
                ),
                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-info",
                    // onClick: () =>
                    //   downloadPdf(
                    //     `/salesorder/${cell.getValue()}/generatepdf2`,
                    //     "GET"
                    //   ),
                  },
                  h("i", { class: "la la-eye fs-2" })
                ),
              ]),
      }),
    ];

    return {
      selected,
      columnsProcess,
      columnsSuccess,
    };
  },
};
</script>

<style></style>
