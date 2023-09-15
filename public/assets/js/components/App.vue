<template>
    <div>
      <h1>Login</h1>
      <form @submit="login" class="login-form">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="formData.email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="formData.password" required />
        </div>
        <div class="form-group">
          <button type="submit">Login</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          email: '',
          password: ''
        }
      };
    },
    methods: {
      login(event) {
        event.preventDefault();
        // Envoyer les données de connexion au backend (Symfony) pour authentification.
        // Vous pouvez utiliser Axios ou une autre bibliothèque pour effectuer une requête AJAX.
        const { email, password } = this.formData;
        // Exemple d'utilisation d'Axios :
        axios
          .post('/login', { email, password })
          .then(response => {
            // Gérer la réponse du backend en fonction de la réussite ou de l'échec de l'authentification.
            if (response.data.status === true) {
              // Rediriger l'utilisateur vers une autre page (par exemple, le tableau de bord) après une connexion réussie.
              this.$router.push('/dashboard');
            } else {
              alert('Échec de l\'authentification');
            }
          })
          .catch(error => {
            console.error(error);
            alert('Erreur lors de la requête de connexion');
          });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Styles spécifiques au composant Login.vue */
  .login-form {
    max-width: 300px;
    margin: 0 auto;
  }
  
  .form-group {
    margin-bottom: 10px;
  }
  
  label {
    display: block;
    font-weight: bold;
  }
  
  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  button {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  </style>
  