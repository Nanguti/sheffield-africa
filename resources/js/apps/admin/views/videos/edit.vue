<template>
    <div class="container">
        <teleport to="#breadcrumb">
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="javascript:;">Videos</a>
                                </li>
                                <li
                                    class="breadcrumb-item active"
                                    aria-current="page"
                                >
                                    <span>Edit Video</span>
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
                                <div
                                    class="col-xl-12 col-md-12 col-sm-12 col-12"
                                >
                                    <h3><b>Edit Video</b></h3>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form @submit.prevent="submitForm">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="post-name">Title</label>
                                        <input
                                            v-model="blog.name"
                                            id="post-name"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Title ..."
                                        />

                                        <div class="text-danger mt-1">
                                            {{ errors.name }}
                                        </div>
                                        <div class="text-danger mt-1">
                                            <div
                                                v-for="message in validationErrors?.name"
                                            >
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="form-group">
                                        <label for="post-content"
                                            > Description</label
                                        >

                                        <quill-editor
                                            v-model:value="blog.content"
                                            :options="options1"
                                            placeholder="Enter Blog Content ..."
                                        ></quill-editor>
                                    </div>

                                    <div
                                        class="custom-file-container"
                                        data-upload-id="mySecondImage"
                                    >
                                        <label
                                            >Brand Logo (Must be image)
                                            <a
                                                href="javascript:void(0)"
                                                class="custom-file-container__image-clear"
                                                title="Clear File"
                                                >x</a
                                            ></label
                                        >
                                        <label
                                            class="custom-file-container__custom-file"
                                            for="main_image"
                                        >
                                            <input
                                                type="file"
                                                class="custom-file-container__custom-file__custom-file-input"
                                                accept="image/jpeg, image/png"
                                                @change="
                                                    blog.main_image =
                                                        $event.target.files[0]
                                                "
                                                id="main_image"
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
                                        <div
                                            class="custom-file-container__image-preview"
                                        ></div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="post-category"
                                            class="form-label"
                                            >Type</label
                                        >

                                        <select class="form-select form-control" name="type" id="type" v-model="blog.type" @change="handleTypeChange">
                                            <option value="">Select Type</option>
                                            <option value="Upload">Upload</option>
                                            <option value="Youtube Url">Youtube Url</option>
                                        </select>

                                        <div class="text-danger mt-1">
                                            {{ errors.type }}
                                        </div>
                                        <div class="text-danger mt-1">
                                            <div
                                                v-for="message in validationErrors?.type"
                                            >
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="custom-file-container"
                                        data-upload-id="myFirstImage"

                                        v-if="blog.type == 'Upload'"

                                        ref="uploadContainer"
                                    >
                                        <label
                                            >Upload Video (Must be mp4)
                                            <a
                                                href="javascript:void(0)"
                                                class="custom-file-container__image-clear"
                                                title="Clear File"
                                                >x</a
                                            ></label
                                        >
                                        <label
                                            class="custom-file-container__custom-file"
                                            for="file_path"
                                        >
                                            <input
                                                type="file"
                                                class="custom-file-container__custom-file__custom-file-input"
                                                accept="video/mp4"
                                                @change="
                                                    blog.file_path =
                                                        $event.target.files[0]
                                                "
                                                id="file_path"
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
                                        <div
                                            class="custom-file-container__image-preview"
                                        ></div>
                                    </div>


                                    <div class="form-group col-md-12" v-if="blog.type == 'Youtube Url'">
                                        <label for="post-name">Youtube Video Url</label>
                                        <input
                                            v-model="blog.video_url"
                                            id="post-name"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Youtube Video Url ..."
                                            @input="validateYoutubeUrl"
                                        />

                                        <div class="text-danger mt-1">
                                            {{ errors.video_url }}
                                        </div>
                                        <div class="text-danger mt-1">
                                            <div
                                                v-for="message in validationErrors?.video_url"
                                            >
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label
                                            for="is_published"
                                            class="col-form-label"
                                            >Shown in About Us</label
                                        >
                                        <div>
                                            <select
                                                v-model="blog.shown_in_about_us"
                                                id="shown_in_about_us"
                                                class="form-select"
                                            >
                                                <option value="">
                                                    Select Shown Status ...
                                                </option>
                                                <option selected value="1">
                                                    Shown
                                                </option>
                                                <option value="0">
                                                    Not Shown
                                                </option>
                                            </select>
                                        </div>

                                        <div class="text-danger mt-1">
                                            {{ errors.shown_in_about_us }}
                                        </div>
                                        <div class="text-danger mt-1">
                                            <div
                                                v-for="message in validationErrors?.shown_in_about_us"
                                            >
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                              

                                   

                                    <div class="form-group col-md-12">
                                        <label
                                            for="is_published"
                                            class="col-form-label"
                                            >Publishing Status</label
                                        >
                                        <div>
                                            <select
                                                v-model="blog.is_published"
                                                id="is_published"
                                                class="form-select"
                                            >
                                                <option value="">
                                                    Select Publishing Status ...
                                                </option>
                                                <option selected value="1">
                                                    Published
                                                </option>
                                                <option value="0">
                                                    Not Published
                                                </option>
                                            </select>
                                        </div>

                                        <div class="text-danger mt-1">
                                            {{ errors.is_published }}
                                        </div>
                                        <div class="text-danger mt-1">
                                            <div
                                                v-for="message in validationErrors?.is_published"
                                            >
                                                {{ message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    :disabled="isLoading"
                                    class="btn btn-primary mt-3"
                                >
                                    <div v-show="isLoading" class=""></div>
                                    <span v-if="isLoading">Processing...</span>
                                    <span v-else>Save</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.ql-container {
    min-height: 250px !important;
    height: 300px !important;
}

.custom-file-container__image-multi-preview {
    height: 200px !important;
}
</style>
<script setup>
import { onMounted, reactive, watchEffect, ref } from "vue";

import { useMeta } from "../../composables/use-meta";
useMeta({ title: "Edit Blog" });

import { useRoute } from "vue-router";
import useVideos from "@/composables/videos";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";

import "../../assets/sass/forms/file-upload-with-preview.min.css";
import FileUploadWithPreview from "file-upload-with-preview";

import Multiselect from "@suadelabs/vue3-multiselect";
import "@suadelabs/vue3-multiselect/dist/vue3-multiselect.css";

import { quillEditor } from "vue3-quill";
import "vue3-quill/lib/vue3-quill.css";

defineRule("required", required);
defineRule("min", min);

// Define a validation schema
const schema = {
    name: "required|min:3",
    type: "required",
    is_published: "required"
};

// Create a form context with the validation schema
const { validate, errors, resetForm } = useForm({ validationSchema: schema });
// Define actual fields for validation

const {
    video: postData,
    getVideo,
    updateVideo,
    validationErrors,
    isLoading,
    getVideoList,
    blogList
} = useVideos();



// Define actual fields for validation
const { value: name } = useField("name", null, { initialValue: "" });
const { value: type } = useField("type", null, {
    initialValue: "",
});
const { value: content } = useField("content", null, {
    initialValue: "",
});
const { value: main_image } = useField("main_image", null, {
    initialValue: "",
});
const { value: file_path } = useField("file_path", null, {
    initialValue: "",
});
const { value: is_published } = useField("is_published", null, {
    initialValue: "",
});
const { value: shown_in_about_us } = useField("shown_in_about_us", null, {
    initialValue: "",
});

const blog = reactive({
    name,
    type,
    content,
    is_published,
    shown_in_about_us,
    main_image: "",
    file_path: "",
});

const handleTypeChange = () => {

    if (blog.type === 'Upload') {
        console.log("test");
        const fileUploadContainer = new FileUploadWithPreview("myFirstImage", {
          images: {
            baseImage: "/assets/images/video_preview.jpg",
            backgroundImage: "",
          },
        });
        
        // Append the file upload container to the specified ref element
        fileUploadContainer.appendTo(document.getElementById('uploadContainer'));
    }

};


const validateYoutubeUrl = () => {
  const youtubeUrlPattern = /^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=/i;
  
  if (!youtubeUrlPattern.test(blog.video_url)) {
    errors.value.video_url = "Please enter a valid YouTube Video URL in the format https://www.youtube.com/watch?v=";
  } else {
    errors.value.video_url = null;
  }
};

const options1 = ref({
    modules: {
        //toolbar: [[{ header: [1, 2, false] }], ["bold", "italic", "underline"], ["image", "code-block"]],
    },
});

const route = useRoute();
function submitForm() {
    validate().then((form) => {
        if (form.valid) {

            updateVideo(blog, []);            
        }
    });
}

function deleteImage(imageId) {
    deleteBlogImage(imageId);
}

const initializeFileUpload = async () => {};

onMounted(() => {
    
    getVideo(route.params.id);

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
            new FileUploadWithPreview("mySecondImage", fileUploadConfig);
        } else {
            new FileUploadWithPreview("mySecondImage", {
                images: {
                    baseImage: "/assets/images/file-preview.png",
                    backgroundImage: "",
                },
            });
        }
    });
 
});
// https://vuejs.org/api/reactivity-core.html#watcheffect

watchEffect(() => {
    blog.id = postData.value.id;
    blog.name = postData.value.name;
    blog.content = postData.value.content;
    blog.type = postData.value.type;
    blog.is_published = postData.value.is_published;
    blog.main_image = postData.value.main_image_path;
    blog.shown_in_about_us = postData.value.shown_in_about_us;
    blog.video_url = postData.value.video_url;


    
});
</script>
