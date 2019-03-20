
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  
  data: {
    hostName: core.baseUrl,
  },

  
  onLoad: function (e) {
    let that = this;
    that.getMemberInfo();
    that.getQrcode();
  },

  
  onShow: function () {

  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        that.setData({
          member: res.data.member
        });
      },
      fail: function (res) {
      }
    });
  },

  //获取二维码
  getQrcode:function(){
    let that = this;
    core.request({
      url: 'api/member/qrcode',
      method: 'GET',
      success: function (res) {
        that.setData({
          qrcode: res.data.qrcode
        });
      },
      fail: function (res) {
      }
    });
  },

  //保存二维码
  // downQrcode:function(){
  //   let that = this;
  //   wx.downloadFile({
  //     url: that.data.hostName+that.data.qrcode, 
  //     success(res) {
  //       if (res.statusCode === 200) {

  //         wx.saveImageToPhotosAlbum({ 
  //           filePath: res.tempFilePath, 
  //           success: function (res) {
  //             wx.showToast({
  //               title: '保存成功',
  //             });
  //           }, 
  //           fail: function (res) {
  //           } 
  //         });

  //       }
        
  //     }
  //   })

  // },

  //转发二维码
  shareQrcode:function(e){
    let that = this;
    if (that.data.qrcode){
      wx.previewImage({
        urls: [e.target.dataset.src]
      });
    }
  },

  
})