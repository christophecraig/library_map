function locate_expl(explId, urlBase) {
    console.log(urlBase)
    let xhr = new XMLHttpRequest();
    xhr.open('GET', urlBase + 'ajax_locate.php?id=' + explId);
    xhr.send();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            leVue.$data.map = JSON.parse(xhr.responseText);
            leVue.$data.showModal = true;
        }
    }
}

Vue.component('modal', {
    props: ['map'],
    data() {
        return {
            selected: ''
        }
    },
    template: `
    <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper"  @click.capture.self="$emit('close')">
        <div class="modal-container" v-html="map">
        </div>
      </div>
    </div>
  </transition>
    `
})

var leVue = new Vue({
    el: '#modal-vue',
    data() {
        return {
            map: '',
            showModal: false
        }
    }
});