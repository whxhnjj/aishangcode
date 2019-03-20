
import { Core } from '../../extend/core.js';
var core = new Core();

var QQMapWX = require('../../extend/qqmap/qqmap-wx-jssdk.min.js');
var qqmapsdk;

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    area:'',
    latlng:'',
    nav_state:0,
    licence_pic:''
  },

  
  onLoad: function (e) {
    let that = this;
    var scene = decodeURIComponent(e.scene);
    if (scene == 'qrcode'){
      that.setData({
        page_state : true
      });
    }
    that.getMapKey();
  },

  
  onShow: function () {
  },

  //获取地图密钥
  getMapKey:function(){
    let that = this;
    core.request({
      url: 'api/complete/key',
      method: 'GET',
      success: function (res) {
        qqmapsdk = new QQMapWX({
          key: res.data.key
        });
      },
      fail: function (res) {
      
      }
    });
  },

  //选项卡切换
  navChange:function(){
    let that = this;
    that.setData({
      area: '',
      latlng: '',
    })
    if(that.data.nav_state == 0){
      that.setData({nav_state:1});
    }else{
      that.setData({nav_state: 0});
    }
  },

  //选择地址区域
  chooseLocation: function () {
    let that = this;
    wx.authorize({
      scope: "scope.userLocation",
      success: function () {
        wx.chooseLocation({
          success: function (loc) {
            qqmapsdk.reverseGeocoder({
              location: {
                latitude: loc.latitude,
                longitude: loc.longitude
              },
              success: function (res) {
                if (res.result.ad_info.city_code) {
                  var area = res.result.ad_info.province + res.result.ad_info.city + res.result.ad_info.district;
                  var latlng = loc.latitude + ',' + loc.longitude;
                  that.setData({
                    area: area,
                    latlng: latlng,
                    address: res.result.address_component.street_number ? res.result.address_component.street_number : res.result.address_component.street
                  });
                } else {
                  wx.showToast({
                    title: '区域可选范围仅限西安市',
                    icon: 'none'
                  })
                }
              },
              fail: function (res) {
                wx, wx.showToast({
                  title: '位置选择失败，请重试',
                  icon: 'none'
                });
              }
            });
          }
        });
      },
      fail: function (res) {
        if (res.errMsg == 'authorize:fail auth deny') {
          wx.openSetting();
        }
      }
    });
  },


  //上传营业执照
  uploadLicence:function(){
    let that = this;
    wx.chooseImage({
      count:1,
      sizeType: ['compressed'],
      sourceType: ['album', 'camera'],
      success: function(licence){
        core.upload({
          url: 'api/complete/license',
          filePath: licence.tempFilePaths[0],
          name:'file',
          success: function (res) {
            that.setData({
              licence_pic:res.data.path
            })
            wx.showToast({
              title: res.msg
            })
          },
          fail: function (res) {
            wx.showToast({
              title: res.msg,
              icon:'none'
            })
          }
        });
      }
    })

  },

  //个人用户提交
  personalSubmit: function (e){
    let that = this;
    that.formSubmit('api/complete/personal',{
      realname: e.detail.value.personal_name,
      unitname: e.detail.value.unitname,
      contacts: e.detail.value.personal_contact,
      area: e.detail.value.area,
      latlng: e.detail.value.latlng,
      address: e.detail.value.personal_address
    })
  },

  //企业用户提交
  companySubmit: function(e){
    let that = this;
    that.formSubmit('api/complete/company', {
      realname: e.detail.value.company_name,
      contacts: e.detail.value.company_contacts,
      area: e.detail.value.area,
      latlng: e.detail.value.latlng,
      address: e.detail.value.company_address,
      bank_name: e.detail.value.bank_name,
      bank_account: e.detail.value.bank_account,
      bank_card: e.detail.value.bank_card,
      charter_number: e.detail.value.licence_number,
      charter_picurl: that.data.licence_pic
    })
  },

  //收纸员提交
  serviceSubmit: function(e){
    let that = this;
    that.formSubmit('api/complete/service', {
      realname: e.detail.value.service_name,
      contacts: e.detail.value.service_contact,
      area: e.detail.value.area,
      latlng: e.detail.value.latlng,
      address: e.detail.value.service_address
    },true);
  },

  //提交函数
  formSubmit:function(url,data,type){
    let that = this;
    core.request({
      url: url,
      method: 'POST',
      data: data,
      success: function (res) {
        app.globalData.is_complete = 1;
        if (type) { 
          app.globalData.member_rule = 1;
        }
        wx.showToast({
          title: '注册成功',
          icon: 'success'
        })
        setTimeout(function () {
          wx.reLaunch({
            url: '../index/index',
          })
        }, 1000)
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  }
})