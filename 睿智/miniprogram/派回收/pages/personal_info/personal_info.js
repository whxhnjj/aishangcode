import {
  Core
} from '../../extend/core.js';
var core = new Core();

var QQMapWX = require('../../extend/qqmap/qqmap-wx-jssdk.min.js');
var qqmapsdk;

Page({


  data: {
    hostName: core.baseUrl,
  },

  onLoad: function() {
    let that = this;
    that.getMapKey();
    that.getMemberInfo();
  },

  onShow: function() {

  },

  //获取用户信息
  getMemberInfo: function() {
    let that = this;
    core.request({
      url: 'api/complete/member',
      method: 'GET',
      success: function(res) {
        that.setData({
          member: res.data.member,
          area: res.data.member.area,
          latlng: res.data.member.latlng,
        })
      },
      fail: function(res) {}
    })
  },

  //获取地图密钥
  getMapKey: function() {
    let that = this;
    core.request({
      url: 'api/complete/key',
      method: 'GET',
      success: function(res) {
        qqmapsdk = new QQMapWX({
          key: res.data.key
        });
      },
      fail: function(res) {

      }
    });
  },

  //选择地址区域
  chooseLocation: function() {
    let that = this;
    wx.authorize({
      scope: "scope.userLocation",
      success: function() {
        wx.chooseLocation({
          success: function(loc) {
            qqmapsdk.reverseGeocoder({
              location: {
                latitude: loc.latitude,
                longitude: loc.longitude
              },
              success: function(res) {
                if (res.result.ad_info.city_code) {
                  var area = res.result.ad_info.province + res.result.ad_info.city + res.result.ad_info.district;
                  var latlng = loc.latitude + ',' + loc.longitude;
                  that.setData({
                    area: area,
                    latlng: latlng,
                    address: res.result.address_component.street_number ? res.result.address_component.street_number : res.result.address_component.street
                  })
                } else {
                  wx.showToast({
                    title: '暂不支持该区域',
                    icon: 'none'
                  })
                }
              },
              fail: function(res) {
                wx,wx.showToast({
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

  //更新用户信息
  updataMemeberInfo: function(e) {
    let that = this;
    var data = {
      realname: e.detail.value.realname,
      contacts: e.detail.value.contacts,
      area: e.detail.value.area,
      latlng: e.detail.value.latlng,
      address: e.detail.value.address,
    };
    var url; 
    if (that.data.member.rule != 0) {
      url = 'service/upgrade'
    }else{
      if (that.data.member.type == 1) {
        data.bank_name = e.detail.value.bank_name;
        data.bank_account = e.detail.value.bank_account;
        data.bank_card = e.detail.value.bank_card;
        data.charter_number = e.detail.value.charter_number;
        data.charter_picurl = that.data.member.charter_picurl;
        url = 'customer/company';
      }else{
        data.unitname = e.detail.value.unitname;
        url = 'customer/personal';
      }
    }
    
    core.request({
      url: 'api/' + url,
      method: 'POST',
      data: data,
      success: function(res) {
        wx.showToast({
          title: res.msg
        });
        setTimeout(function() {
          wx.navigateBack({
            delta: 1
          });
        }, 1000)
      },
      fail: function(res) {
        wx.showToast({
          title: res.msg,
          icon: "none"
        });
      }
    })
  },

  //


  //修改营业执照
  uploadLicence: function() {
    let that = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['compressed'],
      sourceType: ['album', 'camera'],
      success: function(licence) {
        core.upload({
          url: 'api/complete/license',
          filePath: licence.tempFilePaths[0],
          name: 'file',
          success: function(res) {
            that.setData({
              ['member.charter_picurl']: res.data.path
            })
            wx.showToast({
              title: res.msg
            })
          },
          fail: function(res) {
            wx.showToast({
              title: res.msg,
              icon: 'none'
            })
          }
        });
      }
    })
  }
})