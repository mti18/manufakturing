<template>
  <section>
    <Form v-if="openForm" :selected="selected" />
    <div class="card">
      <div class="card-header">
        <div class="card-title w-100">
          <h1>User</h1>
          <button v-if="!openForm" type="button" class="btn btn-primary btn-sm ms-auto" @click="openForm = true">
            <i class="las la-plus"></i>
            User Baru
          </button>
        </div>
      </div>
      <div class="card-body">
        <mti-paginate id="table-user" url="/user/paginate" :columns="columns"></mti-paginate>
      </div>
    </div>
  </section>
</template>

<script>
import { useMutation } from "vue-query";
import { ref, h } from "vue";
import { createColumnHelper } from "@tanstack/vue-table";
const columnHelper = createColumnHelper();

import Form from "./Form.vue";

export default {
  components: {
    Form,
  },
  setup() {
    const selected = ref();
    const openForm = ref(false);
    const columns = [
      columnHelper.accessor("nomor", {
        header: "#",
        style: {
          width: "25px",
        },
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("name", {
        header: "Nama",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("email", {
        header: "Email",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("nip", {
        header: "NIP",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("whatsapp", {
        header: "Whatsapp",
        cell: (cell) => cell.getValue(),
      }),
      columnHelper.accessor("uuid", {
        header: "Aksi",
        cell: (cell) => h('div', { class: 'd-flex gap-2' }, [
            h('button', { class: 'btn btn-sm btn-icon btn-warning', onClick: () => {
              selected.value = cell.getValue();
              openForm.value = true;
            }}, h('i', { class: 'la la-pencil fs-2' })), 
            h('button', { class: 'btn btn-sm btn-icon btn-danger' }, h('i', { class: 'la la-trash fs-2' }))
          ]),
      }),
    ]

    return {
      selected,
      openForm,
      columns,
    }
  }
}
</script>

<style>

</style>