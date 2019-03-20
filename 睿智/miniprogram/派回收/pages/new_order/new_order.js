
import { Core } from '../../extend/core.js';
var core = new Core();

var QQMapWX = require('../../extend/qqmap/qqmap-wx-jssdk.min.js');
var qqmapsdk;

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    is_donation: 0,
    switch_state: false,
    area: '',
    latlng: '',
    address: '',
    waste_type: [],
    picurls: [],
    suitable_time: '',
    date: [['尽快', '周一', '周二', '周三', '周四', '周五', '周六', '周日'], [], []],
    date_index: [0, 0, 0]
  },

  onLoad: function (e) {
    let that = this;
    if (e.is_free == 1) {
      that.setData({
        is_donation: 1,
        switch_state: true
      });
    }
    that.getMapKey();
    that.requestWasteType();
    that.getMemberInfo();
  },

  //日期改变
  dateColumnChange: function (e) {
    // console.log('修改的列为', e.detail.column, '，值为', e.detail.value);
    let that = this;
    var data = {
      date: that.data.date,
      date_index: that.data.date_index
    }
    data.date_index[e.detail.column] = e.detail.value;
    switch (e.detail.column) {
      case 0:
        switch (data.date_index[0]) {
          case 0:
            data.date[1] = [];
            data.date[2] = [];
            break;
          case 1:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 2:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 3:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 4:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 5:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 6:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
          case 7:
            data.date[1] = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',];
            data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',];
            break;
        }
        data.date_index[1] = 0;
        data.date_index[2] = 0;
        break;
      case 1:
        switch (data.date_index[0]) {
          case 0:
            data.date[1] = [];
            data.date[2] = [];
            data.date_index[2] = 0;
            break;
          case 1:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 2:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 3:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 4:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 5:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 6:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
          case 7:
            switch (data.date_index[1]) {
              case 0:
                data.date[2] = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 1:
                data.date[2] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 2:
                data.date[2] = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 3:
                data.date[2] = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 4:
                data.date[2] = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 5:
                data.date[2] = ['12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 6:
                data.date[2] = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 7:
                data.date[2] = ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 8:
                data.date[2] = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 9:
                data.date[2] = ['16:00', '17:00', '18:00', '19:00', '20:00'];
                break;
              case 10:
                data.date[2] = ['17:00', '18:00', '19:00', '20:00'];
                break;
              case 11:
                data.date[2] = ['18:00', '19:00', '20:00'];
                break;
              case 12:
                data.date[2] = ['19:00', '20:00'];
                break;
            }
            data.date_index[2] = 0;
            break;
        }
    }
    that.setData(data);
  },

  dateChange: function (e) {
    let that = this;
    var suitable_time;
    if (that.data.date_index[0] == 0) {
      suitable_time = that.data.date[0][0];
    } else {
      suitable_time = that.data.date[0][that.data.date_index[0]] + ' '
        + that.data.date[1][that.data.date_index[1]] + ' - '
        + that.data.date[2][that.data.date_index[2]];
    }

    that.setData({
      date_index: e.detail.value,
      suitable_time: suitable_time
    })
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    app.getMemberInfo(function (res) {
      that.setData({
        member: res.data.member
      });
    })
  },

  //获取地图密钥
  getMapKey: function () {
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
                console.log(res);
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
                    title: '暂不支持该区域',
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

  //是否公益捐赠
  switchChange: function (e) {
    let that = this;
    that.setData({
      is_donation: e.detail.value ? 1 : 0
    })
  },

  //获取废品类型
  requestWasteType: function () {
    let that = this;
    core.request({
      url: 'api/complete/price',
      method: 'GET',
      success: function (res) {
        var waste_type = res.data.price;
        if (waste_type[0]) {
          waste_type[0].checked = true;
        }
        that.setData({
          waste_type: waste_type,
          wastetype_selected: [waste_type[0].id]
        })
      },
      fail: function (res) {

      }
    });
  },

  //废品类型选择
  checkboxChange: function (e) {
    let that = this;
    that.setData({
      wastetype_selected: e.detail.value
    })
  },

  //本地上传图片
  uploadPic: function () {
    let that = this;

    var num = 9 - that.data.picurls.length;
    if (num > 0) {
      wx.chooseImage({
        count: num,
        sizeType: ['compressed'],
        sourceType: ['album', 'camera'],
        success: function (res) {
          for (var i = 0; i < res.tempFilePaths.length; i++) {
            that.uploadPicurls(res.tempFilePaths[i]);
          }
        }
      });
    } else {
      wx.showToast({
        title: '最多可上传9张图片',
        icon: 'none'
      })
    }
  },

  //删除图片
  deletePic: function (e) {
    let that = this;
    var picurls = that.data.picurls;
    var index = e.currentTarget.dataset.index;
    picurls.splice(index, 1);
    that.setData({
      picurls: picurls
    });
  },


  //上传图片
  uploadPicurls: function (picurls) {
    let that = this;
    core.upload({
      url: 'api/core/photo',
      filePath: picurls,
      name: 'file',
      success: function (res) {
        var picurls = that.data.picurls;
        picurls.push(res.data.path);
        that.setData({
          picurls: picurls
        })
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  },

  //表单提交
  formSubmit: function (e) {
    let that = this;
    core.request({
      url: 'api/customer/order',
      method: 'POST',
      data: {
        suitable_time: that.data.suitable_time,
        realname: e.detail.value.name,
        contacts: e.detail.value.contact,
        area: e.detail.value.area,
        latlng: e.detail.value.latlng,
        address: e.detail.value.address,
        materials_type: that.data.wastetype_selected,
        materials_picurl: that.data.picurls,
        is_free: that.data.is_donation
      },
      success: function (res) {
        wx.showToast({
          title: "发单成功",
          icon: "success"
        })
        setTimeout(function () {
          wx.redirectTo({
            url: '../order_detail/order_detail?id=' + res.data.id
          })
        }, 1000)

      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: "none"
        })
      }
    });
  },

  //formid
  formidSubmit: function (e) {
    let that = this;
    console.log(e.detail.formId)
    if (e.detail.formId && e.detail.formId != 'the formId is a mock one') {

      core.request({
        url: 'api/complete/formid',
        method: 'POST',
        data: {
          formid: e.detail.formId
        },
        success: function (res) {
        },
        fail: function (res) {
        }
      });
    }
  },



})