<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body" v-if="showForm">
            <h1 class="h1 text-center">Descargar Certificado</h1>
            <form @submit.prevent="check">
              <div class="mb-3">
                <label for="email" class="form-label">Correo Electronico</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="name@example.com"
                  v-model="email"
                />
              </div>
              <button class="btn btn-primary">
                {{ buttonName }}
              </button>
            </form>
          </div>
          <div class="card-body" v-else>
            <template v-if="hasCertificado">
              <h2>Encontro el certificado</h2>
              <p>
                El certificado ya se encuentra listo para ser descargado,
                gracias por participar
              </p>
              <a
                v-if="downloadFile"
                :href="downloadFile"
                class="btn btn-primary"
                target="_blank"
              >
                Descargar archivo
              </a>
              <button @click="reset" class="btn btn-secondary">Volver</button>
            </template>
            <template v-if="!hasCertificado">
              <h2>No se encontro el certificado</h2>
              <p>
                El certificado no se encuentra en nuestro sistema, si tienes
                alguna duda puedes consultar a soporte
              </p>
              <button @click="reset" class="btn btn-secondary">Volver</button>
            </template>
            <p></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      email: "",
      user: null,
      loading: false,
    };
  },
  name: "CertificadoComponent",
  methods: {
    check() {
      if (this.loading) {
        return;
      }
      this.$nprogress.start();
      this.loading = true;
      this.$axios
        .post("/api/check_certificate", { email: this.email })
        .then((response) => {
          this.user = response.data;
          this.loading = false;
          this.$nprogress.done();
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
          this.$nprogress.done();
        });
    },
    reset() {
      this.email = "";
      this.user = null;
      this.loading = false;
    },
  },
  computed: {
    downloadFile() {
      if (!this.user || !this.user.id) {
        return null;
      }
      return `/download_certificado?email=${this.email}&key=${this.user.id}`;
    },
    buttonName() {
      return this.loading
        ? "Consultado Certificado..."
        : "Consultar Certificado";
    },
    hasCertificado() {
      if (!this.user) {
        return null;
      }
      return this.user.hasCertificado;
    },
    showForm() {
      return this.hasCertificado === null;
    },
  },
};
</script>
