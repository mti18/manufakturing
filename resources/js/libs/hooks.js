import axios from "@/libs/axios";

const useDelete = ({
  swalMixin = {
    customClass: {
      confirmButton: "btn btn-danger btn-sm",
      cancelButton: "btn btn-secondary btn-sm",
    },
    buttonsStyling: false,
  },
  onSuccess,
}) => {
  const mySwal = Swal.mixin(swalMixin);
  return {
    delete: (url) =>
      mySwal
        .fire({
          title: "Apakah anda yakin?",
          text: "Data yang dihapus tidak dapat dikembalikan!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Ya, hapus!",
          cancelButtonText: "Batalkan!",
          reverseButtons: true,
          preConfirm: () => {
            return axios.delete(url).catch((error) => {
              Swal.showValidationMessage(error.response.data.message);
            });
          },
        })
        .then((result) => {
          if (result.isConfirmed) {
            mySwal.fire("Berhasil!", result.value.data.message, "success");
            onSuccess && onSuccess();
          }
        }),
  };
};

const useDownloadPdf = ({
  swalMixin = {
    customClass: {
      confirmButton: "btn btn-success btn-sm",
      cancelButton: "btn btn-secondary btn-sm",
    },
    buttonsStyling: false,
  },
  onSuccess,
} = {}) => {
  const mySwal = Swal.mixin(swalMixin);
  return {
    download: (url, method = "GET", data = {}) => {
      window.respon = {};
      return mySwal
        .fire({
          title: "Apakah Anda Yakin?",
          text: "Anda Akan Mendownlaod Report Berformat PDF, Mungkin Membutuhkan Waktu Beberapa Detik!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Download Sekarang!",
          showLoaderOnConfirm: true,
          preConfirm: function (login) {
            return axios({
              url,
              method,
              data,
              responseType: "arraybuffer",
            })
              .then((res) => {
                var headers = res.headers;
                var blob = new Blob([res.data], {
                  type: "application/pdf",
                });

                var link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = headers["content-disposition"]
                  .split('filename="')[1]
                  .split('"')[0];
                link.click();

                onSuccess && onSuccess();
                window.respon = { status: true, message: "Berhasil Download!" };
              })
              .catch((error) => {
                window.respon = JSON.parse(
                  String.fromCharCode.apply(
                    null,
                    new Uint8Array(error.response.data)
                  )
                );
              });
          },
        })
        .then(function (result) {
          if (result.isConfirmed) {
            if (window.respon.status) {
              Swal.fire("Berhasil!", "File Berhasil Di Download.", "success");
            } else {
              Swal.fire("Error!", window.respon.message, "error");
            }
          }
        });
    },
  };
};

export { useDelete, useDownloadPdf };
