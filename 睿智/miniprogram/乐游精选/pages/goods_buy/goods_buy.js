
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    mengShow: false,
    aniStyle: true,
    goods_count:1,
    total_price: 0,
    real_price: 0,
    integral_text:'',
    integral_choose: [
      { name: '1', checked: false},
      { name: '0', checked: false }
    ],
    use_int:'',
  },

  onLoad: function(e) {
    let that = this;
    that.setData({
      isIphone: app.globalData.isIphone,
      id:e.id,
      realname: app.globalData.member.realname,
      mobile: app.globalData.member.mobile
    });
    that.getBuyInfo();
    that.getMemberInfo();

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
              that.setData({
                address: res
              });
              // core.request({
              //   url: 'api/address/edit',
              //   data: {
              //     user_name: res.userName,
              //     tel_number: res.telNumber,
              //     province_name: res.provinceName,
              //     city_name: res.cityName,
              //     county_name: res.countyName,
              //     detail_info: res.detailInfo,
              //     postal_code: res.postalCode,
              //     national_code: res.nationalCode
              //   },
              //   method: 'POST',
              //   success: function (res) {
              //     console.log(res.data)
              //     that.setData({
              //       address: res.data.address
              //     })
              //   },
              //   fail: function (res) {
              //   }
              // });

            }
          })
        }
      }
    });
  },

  //获取初始地址
  // getDefaultAddress: function () {
  //   let that = this;
  //   core.request({
  //     url: 'api/address/fetch',
  //     method: 'GET',
  //     success: function (res) {
  //       that.setData({
  //         address: res.data.address
  //       })
  //     },
  //     fail: function (res) {
  //     }
  //   });

  // },

  //获取用户信息
  getMemberInfo: function(){
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        that.setData({
          member_id: res.data.member.id,
        });
      },
      fail: function (res) {
      }
    });

  },

  //获取购买信息
  getBuyInfo:function(){
    let that = this;
    core.request({
      url: 'api/order/verify',
      method: 'GET',
      data: {
        id: that.data.id,
        count: that.data.goods_count
      },
      success: function (res) {
        var goods = res.data.goods;
        goods.goods_pic = goods.goods_pic.split(',');
        goods.price = (goods.price/100).toFixed(2);
        
        if (res.data.pre_int == 0){
          that.setData({
            use_int: 0,
            real_price: goods.price
          });
        }
        that.setData({
          goods: goods,
          total_price: goods.price,
          pre_int: res.data.pre_int,
          pre_cash: (res.data.pre_cash/100).toFixed(2),
          
        });
      },
      fail: function (res) {
      }
    });
  },

  //加
  addNum: function(){
    let that = this;
    var goods_count = that.data.goods_count;
    var total_price = that.data.total_price;
    total_price = that.data.goods.price * ++goods_count;
    core.request({
      url: 'api/order/plusminus',
      method: 'GET',
      data: {
        id: that.data.id,
        count: goods_count
      },
      success: function (res) {
        if (that.data.use_int == 0) {
          that.setData({
            real_price: total_price.toFixed(2),
          });
        }

        if (that.data.use_int == 1) {
          that.setData({
            real_price: (total_price - res.data.pre_cash / 100).toFixed(2),
          });
        }

        that.setData({
          total_price: total_price.toFixed(2),
          goods_count: goods_count,
          pre_int: res.data.pre_int,
          pre_cash: (res.data.pre_cash / 100).toFixed(2),
          integral_text: that.data.use_int == 1 ? '- ¥' + (res.data.pre_cash / 100).toFixed(2) : that.data.integral_text
        });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //减
  subtractNum:function(){
    let that = this;
    var goods_count = that.data.goods_count;
    var total_price = that.data.total_price;
    total_price = that.data.goods.price * --goods_count;
    core.request({
      url: 'api/order/plusminus',
      method: 'GET',
      data: {
        id: that.data.id,
        count: goods_count
      },
      success: function (res) {
        if (that.data.use_int == 0){
          that.setData({
            real_price: total_price.toFixed(2),
          });
        }

        if (that.data.use_int == 1) {
          that.setData({
            real_price: (total_price - res.data.pre_cash / 100).toFixed(2),
          });
        }

        that.setData({
          total_price: total_price.toFixed(2),
          goods_count: goods_count,
          pre_int: res.data.pre_int,
          pre_cash: (res.data.pre_cash/100).toFixed(2),
          integral_text: that.data.use_int == 1 ? '- ¥' + (res.data.pre_cash / 100).toFixed(2) : that.data.integral_text
        });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //弹出层显示
  showPop: function() {
    let that = this;
    that.setData({
      mengShow: true,
      aniStyle: true
    });
  },

  //弹出层隐藏
  outbtn: function () {
    let that = this;
    that.setData({
      aniStyle: false
    })
    setTimeout(function () {
      that.setData({
        mengShow: false
      })
    }, 500)
  },

  //是否使用积分
  integralChange: function (e) {
    let that = this;
    var index = e.target.id;
    var real_price, integral_text, use_int;
    var integral_choose = that.data.integral_choose;

    for (let i = 0; i < integral_choose.length; i++) {
      integral_choose[i].checked = false;
    }
    integral_choose[index].checked = true;

    if (index == 0) {
      use_int = 1;
      real_price = (that.data.total_price - that.data.pre_cash).toFixed(2);
      integral_text = '- ¥' + that.data.pre_cash;
    } else {
      use_int = 0;
      real_price = that.data.total_price;
      integral_text = "不使用";
    }

    that.setData({
      use_int: use_int,
      real_price: real_price,
      integral_text: integral_text,
      integral_choose: integral_choose
    });
  },

  //下单
  placeOrder: function(e){
    let that = this;
    var data = {
      id: that.data.id,
      count: that.data.goods_count,
      use_int: that.data.use_int,
      promotion_id: wx.getStorageSync('member_id') ? wx.getStorageSync('member_id') : that.data.member_id,
      realname: e.detail.value.realname,
      contacts: e.detail.value.contacts,
      remark: e.detail.value.remark
    }
    if (that.data.goods.goods_type == 1){
      if(that.data.address){
        var address = that.data.address.provinceName + that.data.address.cityName +
          that.data.address.countyName + that.data.address.provinceName + that.data.address.detailInfo;
        data.address = address;
        data.realname = that.data.address.userName;
        data.contacts = that.data.address.telNumber;
      }else{
        data.address = '';
        data.realname = '备注';
        data.contacts = 13222222222;
      }
    }
    core.request({
      url: 'api/order/create',
      method: 'POST',
      data: data,
      success: function (res) {
        if (res.data.pay){
          wx.requestPayment({
            timeStamp: res.data.pay.timeStamp,
            nonceStr: res.data.pay.nonceStr,
            package: res.data.pay.package,
            signType: res.data.pay.signType,
            paySign: res.data.pay.paySign,
            success: function () {
              wx.redirectTo({
                url: '../order_detail/order_detail?id=' + res.data.id,
              });
            },
            fail: function () {
              wx.redirectTo({
                url: '../order_detail/order_detail?id=' + res.data.id,
              });
            },
          });
        }else{
          wx.redirectTo({
            url: '../order_detail/order_detail?id=' + res.data.id,
          });
        }
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //阻止冒泡事件
  inbtn: function(e) {}


})