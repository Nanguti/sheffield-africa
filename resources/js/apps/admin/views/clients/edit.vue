<template>
  <div class="container">
    <teleport to="#breadcrumb">
      <ul class="navbar-nav flex-row">
        <li>
          <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:;">Client</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  <span>Edit Client</span>
                </li>
              </ol>
            </nav>
          </div>
        </li>
      </ul>
    </teleport>

    <div class="container">
      <div class="row">
        <div id="" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading pb-0">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h3><b>Edit Client</b></h3>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <form @submit.prevent="submitForm">
                <div class="row">
                    <div class="form-group col-md-6">
                  <label for="post-name">Client Name</label>
                  <input
                    v-model="client.name"
                    id="post-name"
                    type="text"
                    class="form-control"
                    placeholder="Enter Client Name ..."
                  />

                  <div class="text-danger mt-1">
                    {{ errors.name }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.name">
                      {{ message }}
                    </div>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="post-phone">Client Phone</label>
                  <input
                    v-model="client.phone"
                    id="post-phone"
                    type="text"
                    class="form-control"
                    placeholder="Enter Client Phone ..."
                  />

                  <div class="text-danger mt-1">
                    {{ errors.phone }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.phone">
                      {{ message }}
                    </div>
                  </div>
                </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                  <label for="post-email">Client Email</label>
                  <input
                    v-model="client.email"
                    id="post-email"
                    type="email"
                    class="form-control"
                    placeholder="Enter Client Email ..."
                  />

                  <div class="text-danger mt-1">
                    {{ errors.email }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.email">
                      {{ message }}
                    </div>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="post-address">Client Address</label>
                  <input
                    v-model="client.address"
                    id="post-address"
                    type="text"
                    class="form-control"
                    placeholder="Enter Client address ..."
                  />

                  <div class="text-danger mt-1">
                    {{ errors.address }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.address">
                      {{ message }}
                    </div>
                  </div>
                </div>
                </div>

                <div class="form-group">
                  <label for="post_description">Description</label>
                  <textarea
                    v-model="client.description"
                    id="post_description"
                    class="form-control"
                    placeholder="Enter Description ..."
                  ></textarea>

                  <div class="text-danger mt-1">
                    {{ errors.description }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.description">
                      {{ message }}
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div
                    class="custom-file-container"
                    data-upload-id="myFirstImage"
                  >
                    <label
                      >Upload Client Logo
                      <a
                        id="brand_image"
                        href="javascript:void(0)"
                        class="custom-file-container__image-clear"
                        title="Clear Image"
                        >x</a
                      ></label
                    >
                    <label class="custom-file-container__custom-file">
                      <input
                        type="file"
                        class="custom-file-container__custom-file__custom-file-input"
                        accept="image/*"
                        @change="client.main_image = $event.target.files[0]"
                      />
                      <input
                        type="hidden"
                        name="MAX_FILE_SIZE"
                        value="10485760"
                      />
                      <span
                        class="custom-file-container__custom-file__custom-file-control"
                      ></span>
                    </label>
                    <div class="custom-file-container__image-preview"></div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="is_published" class="col-form-label"
                    >Publishing Status</label
                  >
                  <div>
                    <select
                      v-model="client.is_published"
                      id="is_published"
                      class="form-select"
                    >
                      <option selected value="1">Published</option>
                      <option value="0">Not Published</option>
                    </select>
                  </div>

                  <div class="text-danger mt-1">
                    {{ errors.is_published }}
                  </div>
                  <div class="text-danger mt-1">
                    <div v-for="message in validationErrors?.is_published">
                      {{ message }}
                    </div>
                  </div>
                </div>

                <button :disabled="isLoading" class="btn btn-primary mt-3">
                  <div v-show="isLoading" class=""></div>
                  <span v-if="isLoading">Processing...</span>
                  <span v-else>Update</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, reactive, watchEffect } from "vue";

import { useRoute } from "vue-router";
import useClients from "@/composables/clients";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import FileUploadWithPreview from "file-upload-with-preview";
import "../../assets/sass/forms/file-upload-with-preview.min.css";

import "@suadelabs/vue3-multiselect/dist/vue3-multiselect.css";

import { useMeta } from "../../composables/use-meta";
useMeta({ title: "Edit Client Category" });

defineRule("required", required);
defineRule("min", min);

// Define a validation schema
const schema = {
  name: "required|min:3",
  phone: "required|min:3",
  email: "required|min:3",
  address: "required|min:3",
  description: "required|min:3",
  is_published: "required",
};

// Create a form context with the validation schema
const { validate, errors, resetForm } = useForm({ validationSchema: schema });
// Define actual fields for validation

const {
  client: postData,
  getClient,
  updateClient,
  validationErrors,
  isLoading,
  getClientList,
} = useClients();

const { value: name } = useField("name", null, { initialValue: "" });
const { value: phone } = useField("phone", null, { initialValue: "" });
const { value: email } = useField("email", null, { initialValue: "" });
const { value: address } = useField("address", null, { initialValue: "" });
const { value: description } = useField("description", null, {
  initialValue: "",
});
const { value: is_published } = useField("is_published", null, {
  initialValue: "",
});

const client = reactive({
  name,
  phone,
  email,
  address,
  description,
  is_published,
});
const route = useRoute();
function submitForm() {
  validate().then((form) => {
    console.log(form.valid)
    if (form.valid) {
      updateClient(client);
    }
  });
}
onMounted(() => {
  getClient(route.params.id);
  getClientList();

  watchEffect(() => {
    if (postData.value && postData.value.main_image_path) {
      const mainImagePath = postData.value.main_image_path;

      // Define the variable
      const mainImage = "/storage/" + mainImagePath;

      // Create the configuration object with the variable value
      const fileUploadConfig = {
        images: {
          baseImage: mainImage,
          backgroundImage: "",
        },
      };

      // Pass the configuration object to FileUploadWithPreview
      new FileUploadWithPreview("myFirstImage", fileUploadConfig);
    }
  });
});
// https://vuejs.org/api/reactivity-core.html#watcheffect

watchEffect(() => {
  client.id = postData.value.id;
  client.name = postData.value.name;
  client.phone = postData.value.phone;
  client.email = postData.value.email;
  client.address = postData.value.address;
  client.description = postData.value.description;
  client.is_published = postData.value.is_published;

  console.log(postData.value);
});
</script>
