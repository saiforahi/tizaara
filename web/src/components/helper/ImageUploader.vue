<template>
  <div class="uploader"
       @dragenter="OnDragEnter"
       @dragleave="OnDragLeave"
       @dragover.prevent
       @drop="onDrop"
       onclick="document.getElementById('file').click()"
       :class="{ dragging: isDragging }">
    <div v-show="!image.length">
      <i class="fas fa-cloud-upload-alt"></i>
      <p class="mb-0">{{ $t("message.image_uploader.drag_your_images_here")}}</p>
      <div>{{ $t("message.image_uploader.or")}}</div>
      <div class="file-input">
        <label>{{ $t("message.image_uploader.select_file")}}</label>
        <input type="file" id="file" @change="onInputChange">
      </div>
    </div>

    <div class="images-preview" v-show="image.length">
      <div class="img-wrapper">
        <img v-lazy="image" :alt="`Image Uplaoder ${image}`">

      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['image'],
  name: "ImageUploader",
  data: () => ({
    isDragging: false,
    dragCount: 0,
  }),
  methods: {
    OnDragEnter(e) {
      e.preventDefault();

      this.dragCount++;
      this.isDragging = true;
      return false;
    },
    OnDragLeave(e) {
      e.preventDefault();
      this.dragCount--;
      if (this.dragCount <= 0)
        this.isDragging = false;
    },
    onInputChange(e) {
      const files = e.target.files[0];
      this.addImage(files);
    },
    onDrop(e) {
      e.preventDefault();
      e.stopPropagation();
      this.isDragging = false;
      const files = e.dataTransfer.files[0];

      this.addImage(files);

    },
    addImage(file) {
      if (!file.type.match('image.*')) {
        swal.fire(
            this.$t("message.common.oops"),
            `${file.name}` + this.$t("message.image_uploader.not_image"),
            'error'
        );
        return;
      }

      if (file['size'] > 2111775) {
        swal.fire(
            this.$t("message.common.oops"),
            this.$t("message.image_uploader.uploading_learge_file"),
            'error'
        );
        return;
      }
      const img = new Image(),
          reader = new FileReader();
      reader.onload = (e) => this.$emit('update-parent', reader.result);
      reader.readAsDataURL(file);
    },
  }
}
</script>

<style lang="scss" scoped>
.uploader {
  width: 100%;
  background: #ffffff;
  color: #333;
  padding: 5px 0;
  text-align: center;
  border-radius: 10px;
  cursor: pointer;
  font-size: 11px;
  box-shadow: 0 0 2px 0 #00000067;
  position: relative;

  &.dragging {
    background: #fff;
    color: #2196F3;
    border: 1px dashed #2196F3;

    .file-input label {
      background: #2196F3;
      color: #fff;
    }
  }

  i {
    font-size: 35px;
  }

  .file-input {
    width: 100px;
    margin: auto;
    height: 45px;
    position: relative;

    label,
    input {
      background: #333;
      color: #ffffff;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      font-size: 14px;
      padding: 4px;
      border-radius: 14px;
      margin-top: 0;
      border: 1px solid #353333;
      cursor: pointer;
    }

    input {
      opacity: 0;
      z-index: -2;
    }
  }

  .images-preview {
    display: flex;
    flex-wrap: wrap;

    .img-wrapper {
      width: 100%;
      margin: 0 5px;

      img {
        width: 100%;
        max-height: 180px;
        border-radius: 7px;
      }
    }
  }
}
</style>
