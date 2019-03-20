
var app = getApp();
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  //页面的初始数据
  data: {
    hostName: core.baseUrl,
    address: {},
    remark: '',
    kd_array: ['自取', '快递'],
    deliver:1,
    btn_state:false
  },

  //配送方式选择
  bindPickerChange: function (e) {
    let that =this;
    var all_price;
    that.setData({
      deliver: e.detail.value
    })
    if (that.data.deliver == 1){
      all_price = that.data.price + that.data.post_price;
    }else{
      all_price = that.data.price;
    }
    that.setData({
      all_price: all_price
    })
  },

  //生命周期函数--监听页面加载
  onLoad: function (e) {
    let that = this;

    that.setData({
      isIphoneX: app.globalData.isIphoneX
    })

    //获取用户首次授权设置
    wx.getSetting({
      success(res) {
        if (!res.authSetting['scope.address']) {
          wx.authorize({
            scope: 'scope.address',
          });
        }
      }
    });

    that.getDefaultAddress();

    //请求订单信息
    core.request({
      url: 'api/submit/show',
      data: {
        goods: e.goods
      },
      method: 'POST',
      success: function (res) {
        var data = res.data.info;
        var all_price = data.price + data.post_price;
        for (var i = 0; i < data.goods.length; i++) {
          data.goods[i].spec_value = JSON.parse(data.goods[i].spec_value).toString()
        }
      
        that.setData({
          goods: data.goods,
          price: data.price,
          post_price: data.post_price,
          all_price: all_price,
          shop: data.shop,
        })
      },
      fail: function (res) {

      }
    });
  },

  //选择地址
  selectedAddress: function () {
    let that = this;
    wx.getSetting({
      success(res) {
        if (!res.authSetting['scope.address']) {
          wx.openSetting();
        } else {
          //打开选择地址
          wx.chooseAddress({
            success: function (res) {
              core.request({
                url: 'api/address/edit',
                data: {
                  user_name: res.userName,
                  tel_number: res.telNumber,
                  province_name: res.provinceName,
                  city_name: res.cityName,
                  county_name: res.countyName,
                  detail_info: res.detailInfo,
                  postal_code: res.postalCode,
                  national_code: res.nationalCode
                },
                method: 'POST',
                success: function (res) {
                  console.log(res.data)
                  that.setData({
                    address: res.data.address
                  })

                },
                fail: function (res) {


                }
              });

            }
          })
        }
      }
    });
  },

  //获取初始地址
  getDefaultAddress: function () {
    let that = this;
    core.request({
      url: 'api/address/fetch',
      method: 'GET',
      success: function (res) {  
        that.setData({
          address: res.data.address
        })
      },
      fail: function (res) {
      }
    });

  },

  //提交订单
  submitOrder: function () {
    let that = this;
    that.setData({btn_state: true});
    if (!that.data.address.id){
      wx.showToast({
        title: '请选择收货地址',
        icon: 'none'
      })
      that.setData({ btn_state: false });
      return;
    }
    
    var goods = [];
    for (var i = 0; i < that.data.goods.length; i++) {
      goods.push({
        id: that.data.goods[i].id,
        spec_value: JSON.stringify(that.data.goods[i].spec_value.split(",")),
        count: that.data.goods[i].count
      });
    }
    core.request({
      url: 'api/trade/add',
      data: {
        addr_id: that.data.address.id,
        goods: JSON.stringify(goods),
        post_type: that.data.deliver,
        remark: that.data.remark
      },
      method: 'POST',
      success: function (res) {
        wx.requestPayment({
          'timeStamp': res.data.pay.timeStamp,
          'nonceStr': res.data.pay.nonceStr,
          'package': res.data.pay.package,
          'signType': res.data.pay.signType,
          'paySign': res.data.pay.paySign,
          'success': function () {
            that.setData({ btn_state: false })
            wx.redirectTo({
              url: '../buy_success/buy_success?id='+res.data.id+'&price='+that.data.all_price
            })
            
          },
          'fail': function () {
            that.setData({ btn_state: false })
            wx.redirectTo({
              url: '../trade_detail/trade_detail?id='+res.data.id
            })
            
          }
        })
      },
      fail: function (res) {
        wx.showToast({
          title: '订单提交失败，请重新下单',
          icon: 'none'
        })
        that.setData({ btn_state: false })
      }
    });
    
  },

  //获取备注
  getRemark: function (e) {
    let that = this;
    that.setData({
      remark: e.detail.value
    })
  }
})