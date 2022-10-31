import axios from "@/libs/axios";

export const useDelete = ({ swalMixin = {
  customClass: {
    confirmButton: "btn btn-danger btn-sm",
    cancelButton: "btn btn-secondary btn-sm",
  },
  buttonsStyling: false,
}, onSuccess }) => {
  const mySwal = Swal.mixin(swalMixin);
  return {
    'delete': (url) => mySwal
      .fire({
        title: "Apakah anda yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batalkan!",
        reverseButtons: true,
        preConfirm: () => {
          return axios
            .delete(url)
            .catch((error) => {
              Swal.showValidationMessage(error.response.data.message);
            });
        },
      })
      .then((result) => {
        if (result.isConfirmed) {
          mySwal.fire("Berhasil!", result.value.data.message, "success");
          onSuccess && onSuccess();
        }
      })
  }
}