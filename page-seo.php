<?php
function registerSeoAdminMenu() {
    add_menu_page(
        'Seo',
        'Seo',
        'manage_options',
        'gestion-seo',
        'seo_page',
        'dashicons-admin-tools',

    );
}
add_action( 'admin_menu', 'registerSeoAdminMenu' );

function seo_page(){
    gestionSeo();
}

function gestionSeo(){
    $args = array('post_type' => "page",
        'posts_per_page' => -1,
        'cache_results'  => false,
        'no_found_rows'  => true,);
    $pages = get_posts($args);
    ?>
    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        .mb-20 {
            margin-bottom: 20px !important;
        }
        .inputContainer {
            display: flex;
            flex-direction: column;
            row-gap: 15px;
        }
        .progressTitre {
            height: 10px;
            width: 0;
            max-width: 100%;
            transition: all .2s;

        }
        .progressTitre2 {
            height: 10px;
            width: 0;
            max-width: 100%;
            transition: all .2s;

        }
        .containerProgress {
            width: 100%;
            border: 1px solid #ccc;
            height: 10px;
        }
        .container {
            display: flex;
            gap: 20px;
        }
        .left {
            width: 50%;
        }
        .right{
            width: 50%;
            display: flex;
            justify-content: center;
        }
    </style>
    <div class="wrap" id="app">
        <div style="margin-top: 20px;">
            <label style="margin-right: 15px" for="page">Page</label>
            <select @change="getSeo" id="page" v-model="page">
                <option value="">Choisir une page</option>
                <?php foreach ($pages as $page):?>
                    <option value="<?=$page->ID?>"><?=$page->post_title?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="container">
            <div class="left">
        <div v-if="page !== ''">
        <div v-if="titre !== ''">
            <div class="inputContainer mb-20">
                <label for="titre">Titre</label>
                <input type="text" id="titre" :value="titre" @input="checkTitre(titre,'titre',50,70,'progress3',$event,1.43)">
            </div>
            <div class="containerProgress mb-20">
                <div ref="progress3" class="progressTitre" id="progress3"></div>
            </div>

            <div class="inputContainer mb-20">
                <label for="description">Description</label>
                <textarea rows="8" id="description" @input="checkTitre(description,'description',130,170,'progress4',$event,0.59)" :value="description"></textarea>
            </div>
            <div class="containerProgress mb-20">
                <div ref="progress4" class="progressTitre" id="progress4"></div>
            </div>
            <button @click="editSeo" class="button">Valider</button>
        </div>
        <div v-else>
            <div class="inputContainer mb-20">
                <label for="new_titre">Titre</label>
                <input type="text" id="new_titre" @input="checkTitre(new_titre,'new_titre',50,70,'progress',$event,1.43)" :value="new_titre">
            </div>
            <div class="containerProgress">
                <div ref="progress" class="progressTitre" id="progressTitre"></div>
            </div>
            <div class="inputContainer mb-20">
                <label for="new_description">Description</label>
                <textarea rows="8" @input="checkTitre(new_description,'new_description',130,170,'progress2',$event,0.59)" id="new_description" :value="new_description"></textarea>
            </div>
            <div class="containerProgress mb-20">
                <div ref="progress2" class="progressTitre2" id="progress2"></div>
            </div>
            <button @click="addSeo" class="button">Ajouter</button>
        </div>
        </div>
            </div>
        <div class="right">
            <div>
            <h2>Bonnes pratiques SEO</h2>
            <h3>Titre</h3>
            <ul>
                <li>- Inclure des mots-clés</li>
                <li>- Entre 60 et 70 caractères</li>
                <li>- Ne pas écrire le nom de la société</li>
                <li>- Il doit être unique par page</li>
            </ul>
            <h3>Description</h3>
            <ul>
                <li>- Entre 150 et 170 caractères</li>
                <li>- Elle doit être unique par page</li>
            </ul>
            </div>
        </div>
        </div>
    </div>
    <script>
        Vue.createApp({
            data(){
                return {
                    page: "",
                    titre: "",
                    description: "",
                    new_titre: "",
                    new_description: "",

                }
            },
            mounted() {

            },
            methods: {
                getSeo(){
                    let data = new FormData();
                    data.append('action','ajaxgetseo');
                    data.append('page',this.page);
                    axios.post(window.location.origin + "/wp-admin/admin-ajax.php",data)
                        .then(res => {
                            let data = res.data;
                            if(data !== null) {
                                this.titre = data.titre;
                                this.description = data.description;
                                setTimeout(() => {
                                    this.setProgress('titre','progress3',1.43,50,70);
                                    this.setProgress('description','progress4',0.59,130,170);
                                },1000)

                            } else {
                                this.titre = "";
                                this.description = "";
                            }
                        })
                        .catch(err => console.log(err))
                },
                addSeo(){
                    let data = new FormData();
                    data.append('action','ajaxaddseo');
                    data.append('page',this.page);
                    data.append('titre',this.new_titre);
                    data.append('description',this.new_description);
                    axios.post(window.location.origin + "/wp-admin/admin-ajax.php",data)
                        .then(res => {
                            if(res.data === 'ok'){
                                alert("Données enregistrées")
                                window.location.reload();
                            } else {
                                alert("Erreur")
                            }
                        })
                        .catch(err => console.log(err))
                },
                editSeo(){
                    let data = new FormData();
                    data.append('action','ajaxeditseo');
                    data.append('page',this.page);
                    data.append('titre',this.titre);
                    data.append('description',this.description);
                    axios.post(window.location.origin + "/wp-admin/admin-ajax.php",data)
                        .then(res => {
                            if(res.data === 'ok'){
                                alert("Données enregistrées")
                                window.location.reload();
                            } else {
                                alert("Erreur")
                            }
                        })
                        .catch(err => console.log(err))
                },
                checkTitre(item,input,value1,value2,ref,event,calcul){
                    let progress = this.$refs[ref];
                    let width = progress.style.width;

                    this[input] = event.target.value;
                    let titreLength = this[input].length;


                    if (event.target.value.length > item.length) {


                        if (width == 0 || width === "0%") {

                            progress.style.width =  titreLength*calcul + "%";
                        } else {

                            let floatWidth = parseFloat(width);
                            let newWidth = floatWidth + calcul;
                            progress.style.width = newWidth + "%";

                        }
                    } else if(event.target.value.length < item.length) {

                        if(titreLength === 0){
                            progress.style.width =  "0%";
                        } else {
                            let floatWidth = parseFloat(width);
                            let newWidth = floatWidth - calcul;
                            progress.style.width = newWidth + "%";
                        }

                    }

                    if (titreLength < value1) {
                        progress.style.backgroundColor = 'orange'
                    } else if (titreLength >= value1 && titreLength <= value2) {
                        progress.style.backgroundColor = 'green'
                    } else {
                        progress.style.backgroundColor = 'red'
                    }
                },
                setProgress(input,ref,calcul,value,value2){
                    let progress = this.$refs[ref];
                    //let width = progress.style.width;
                    let length = this[input].length;
                    //let floatWidth = parseFloat(width);
                    let newWidth = length*calcul;

                    progress.style.width = newWidth + "%";
                    if (length < value) {
                        progress.style.backgroundColor = 'orange'
                    } else if (length >= value && length <= value2) {
                        progress.style.backgroundColor = 'green'
                    } else {
                        progress.style.backgroundColor = 'red'
                    }
                }







            }
        }).mount('#app')
    </script>
<?php
}