<template>
    <div>
        <main class="main">
            
            <div class="page-content">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-10 offset-lg-1">

                            <h2 class="about-us-title"> Brochures & Catalogs </h2><!-- End .title -->

                            <router-link to="/media" class="btn btn-primary btn-round btn-shadow float-right"><i class="icon-long-arrow-left"></i><span>Back to Media Center</span></router-link>

                            <p class="lead about-us-lead text-primary mb-1">Explore Our Brochures & Catalogs</p>

                            <p class="about-us-text mb-2">Click on the documents to view</p>

                           
                            <div class="row media-center">


                                <div class="col-lg-9">

                                  <VuePdfApp style="height: 100vh;" :pdf="currentDocument"></VuePdfApp>
                                  
                                </div>

                                <aside class="col-lg-3 aside-documents">

                                  <div class="sidebar">
                                 
                                    <div class="widget widget-cats">

                                       <!--  <h3 class="widget-title"><b>Brochures</b></h3> -->

                                      

                                        <ul class="ul-pdf-view-documents">
                                          <li v-for="brochure in brochures" :key="brochure.id"  @click="loadDocument('/storage/' + brochure.publication_file, brochure.id)">

                                            <a :class="{ 'active-document': isActive(brochure.id) }" href="#"> 
                                            <img src="/assets/images/pdf.png" />
                                            
                                              <span>
                                              {{ brochure.name }}
                                            </span>
                                           </a>
                                          </li>
                                        </ul>

                                    </div><!-- End .widget -->

                             
                             
                                  </div>
                                  
                                </aside>

                           



                            </div>

              
                        

                        </div>


                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .page-content -->
        </main><!-- End .main -->
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, watchEffect } from 'vue';
// import axios from "axios";
import { useRoute } from "vue-router";
import { useMeta } from "../../admin/composables/use-meta";


import VuePdfApp from 'vue3-pdf-app';
import "vue3-pdf-app/dist/icons/main.css";

useMeta({ title: "Brochures & Catalogs | Media Center" });


const route = useRoute()
  const currentRoute = ref(route);

  const blogs = ref([]);
  const brochures = ref([]);
  const newsletters = ref([]);
  const videos = ref([]);


  

  // Fetch products based on the current page
  const fetchMediaCenter = async () => {

      try {
        const response = await axios.get('/api/get-media-center', {
         
        });
       
        newsletters.value = response.data.newsletters;
        brochures.value = response.data.brochures;
        
      } catch (error) {
        console.error(error);
      }
  };

  onMounted(() => {
      fetchMediaCenter();
  });

    const currentDocument = ref(null);

    const selectedBrochureId = ref(null);

    const loadDocument = (documentPath, brochureId) => {
      currentDocument.value = documentPath;
      selectedBrochureId.value = brochureId;
    };

    const isActive = (brochureId) => {
      return selectedBrochureId.value === brochureId;
    };
  

  
</script>

<style scoped>

    @media screen and (min-width: 768px){

        .blogs .entry-list .entry-media {

            max-height: 190px;
            overflow: hidden;

        }

        .blogs .entry-title {
            font-size: 2.3rem;
        }
    }

    .posts-list li {
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
    }

    .ul-pdf-view-documents li {
        display: flex;
        /*background-color: #555;*/
        /*margin-top: 1.4rem;*/
        /*padding: 0.8rem;*/
        /*border-radius: 10px;*/
        /*box-shadow: -3px 4px 9px 1px #4c4c4c;*/
    }
    .ul-pdf-view-documents li a {
        display: flex;
        font-size: 1.4rem;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
        color: #000000;
        font-weight: 450;
        width: 100%;
        height: 100%;
    }

    .ul-pdf-view-documents li .active-document {
      background-color: #000000 !important;
      padding-top: 8px !important;
      padding-bottom: 8px !important;
    }

    .ul-pdf-view-documents li .active-document span {
      color: #ffffff !important;
    }

    .ul-pdf-view-documents li span {
        font-size: 1.4rem;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
        color: #000000;
        font-weight: 450;
        width: 100%;
        height: 100%;
    }

    .ul-pdf-view-documents li img {
        width: 30px !important;
        height: 30px !important;
    }

    .aside-documents {

      max-height: 100vh;
      overflow-y: auto;
      background-color: #f2efef;
      padding-top: 20px;
      padding-bottom: 20px;
    }

    .aside-documents::-webkit-scrollbar {
      width: 9px; /* Adjust the width as needed */
    }

    .aside-documents::-webkit-scrollbar-thumb {
      background-color: #888; /* Color of the scrollbar thumb */
    }

    .aside-documents::-webkit-scrollbar-track {
      background-color: #eee; /* Color of the scrollbar track */
    }

</style>

