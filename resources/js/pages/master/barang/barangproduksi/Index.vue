<template>
  <section>
    <Form v-if="openForm" :selected="selected" />
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>Barang Produksi</h1>
          <button
            v-if="!openForm"
            type="button"
            class="btn btn-primary btn-sm ms-auto"
            @click="openForm = true"
          >
            <i class="las la-plus"></i>
            Produksi Barang Baru
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate
          id="table-barangproduksi"
          url="/barangproduksi/paginate"
          :columns="columns"
        ></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, h } from "vue";
import { useQueryClient } from "vue-query";
import { useMutation } from "vue-query";
import { createColumnHelper } from "@tanstack/vue-table";

const columnHelper = createColumnHelper();

import Form from "./Form.vue";
import axios from "@/libs/axios";
import { useDelete } from "@/libs/hooks";

export default {
  components: {
    Form,
  },
  setup() {
    const queryClient = useQueryClient();
    const selected = ref();
    const openForm = ref(false);

    const { delete: deleteBarangProduksi } = useDelete({
      onSuccess: () => {
        queryClient.invalidateQueries(["/barangproduksi/paginate"]);
      },
    });

    const genBarangProduksi = (data) => {
      const mySwal = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success btn-sm",
          cancelButton: "btn btn-secondary btn-sm",
        },
        buttonsStyling: false,
      });

      // var uuid = data.uuid;
      mySwal
        .fire({
          width: 500,
          title: "Apakah anda yakin ingin memproduksi dengan bahan ini?",
          html: `<table type="produce" id="bahan" class="table table-bordered table-responsive table-striped " border=1 responsive>
              <thead> 
                <tr>
                  <td>#</td>
                  <td>Bahan</td>
                  <td>Volume</td>
                </tr>
              </thead>
              <tbody>
                ${data.barangproduksibarangmentahs
                  .map(
                    (item, i) => `
                    <tr >
                      <td>${i + 1}</td>
                      <td class="text-start">${
                        item.barang_mentah?.nm_barangmentah
                      }</td>
                      <td class="text-start">${item.stok_digunakan}</td>
                    </tr>
                `
                  )
                  .join("")}
              </tbody>
            </table>
            <input type="date" >
            Memproduksi akan mengurangi barang mentah dan menambah barang jadi
            `,
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Ya, Produksi!",
          cancelButtonText: "Batalkan!",
          reverseButtons: true,
          preConfirm: () => {
            return axios
              .post(`/barangproduksi/${data.uuid}/produce`)
              .catch((error) => {
                Swal.showValidationMessage(error.response.data.message);
              });
          },
          confirm: () => {},
        })
        .then((result) => {
          if (result.isConfirmed) {
            mySwal.fire("Berhasil!", result.value.data.message, "success");
            onSuccess && onSuccess();
          }
        });
    };

    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("barangproduksibarangjadi.kd_barang_jadi", {
        header: "Kode Barang",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("barangproduksibarangjadi.nm_barang_jadi", {
        header: "Nama Barang Jadi",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("stokbarang", {
        header: "Stok Jadi",
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
                      deleteBarangProduksi(
                        `/barangproduksi/${cell.getValue()}/destroy`
                      );
                    },
                  },
                  h("i", { class: "la la-trash fs-2" })
                ),

                h(
                  "button",
                  {
                    class: "btn btn-sm btn-icon btn-success",
                    onClick: () => genBarangProduksi(cell.row.original),
                  },
                  h("i", { class: "la la-tools fs-2" })
                ),
              ]),
      }),
    ];

    return {
      selected,
      openForm,
      // produce,
      columns,
    };
  },
};
</script>

<style></style>
