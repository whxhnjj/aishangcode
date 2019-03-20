//index.js
//获取应用实例
const app = getApp()
const rate = 0.02;

Page({
  data: {
    reward: 0,
    divisor: 1,
    alert:'不能超过10000',
    alertclass:'hide',
    actionSheetLevelItems: ['你是不是最可爱的人', '你为什么不说话', '优秀好市民', '美丽的神话'],
    addLevelData: "你是不是最可爱的人",
    submitText:'发赏金',
    service:'0.00',
    nullHouse: true, 
  },
  //说明是否选取相册
  depict: function () {
    var that = this;
    this.setData({
      nullHouse: false, //弹窗显示
    })
  },
  //说明是否选取相册
  depict2: function () {
    var that = this;
    this.setData({
      nullHouse: true, //弹窗显示
    })
  },



  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })

    app.getUserOpenId(function (err, openid) {
      if (!err) {
        wx.showLoading({ title: '加载中' });
        wx.request({
          url: 'https://womendehunli.com/api/user/getuser',
          method: 'POST',
          data: {
            openid: openid,
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              that.setData({
                user: res.data.data,
              });
              wx.hideLoading();
            } else {
              wx.showToast({ title: '数据加载失败，刷新试试。' });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '数据加载失败' });
          }
        });

      } else {
        console.log('err:', err)
        self.setData({
          loading: false
        })
      }
    })
  },
  addLevel: function (event) {
    this.setData({
      addLevelDis: true
    })
    var that = this
    wx.showActionSheet({
      itemList: that.data.actionSheetLevelItems,
      success: function (res) {
        if (!res.cancel) {
          that.setData({
            addLevelData: that.data.actionSheetLevelItems[res.tapIndex]
          })
        }
        that.setData({
          addLevelDis: false
        })
      }
    })
  },
  /**
  * 判断输入是否正确
  */
  /**总赏金 */
  input:function(e){
    //console.log(e)
    var value = e.detail.value;
    //赏金
    if(e.currentTarget.id == 'reward')
    {

      //不小于10000
      if (value>10000){
        this.setData({
          reward: 10000,
          alert:'总赏金不能超过10000元',
          alertclass:'show'
        })
        value = 10000;
      } 

      //均值不小于1
      if (value / this.data.divisor < 1) {
        this.setData({
          reward: value,
          alert: '每人获得的赏金不能低于1元',
          alertclass: 'show'
        })
        return value;
      }

      //处理小数为2位
      value = Math.floor(value * 100) / 100;
      this.setData({
        reward: value,
        alert: '',
        alertclass: 'hide'
      })


      console.log(e)
      var sub = value * (rate + 1) - this.data.user.balance

      var submitText = '发赏金'

      if (sub > 0) {
        submitText='还需支付' + sub + '元'
      }

      this.setData({
        service: value * rate,
        submitText: submitText
      })

      return value;
    }




    //个数
    if (e.currentTarget.id == 'divisor') {
      if (value > 100) {
        this.setData({
          divisor: 100,
          alert: '个数不能超过100个',
          alertclass: 'show'
        })
        return 100;
      }

      //均值不小于1
      if (this.data.reward / value < 1 && this.data.reward > 0) {
        this.setData({
          divisor: value,
          alert: '每人获得的赏金不能低于1元',
          alertclass: 'show'
        })
        return value;
      }

      //取整
      value = Math.floor(value);
      this.setData({
        divisor: value,
        alert: '',
        alertclass: 'hide'
      })
      return value;

    }
   

  },



  /**
  * 调起支付
  */
  formSubmit: function (e) {

    console.log(e.detail.value.title)
    var title = e.detail.value.title; //主题
    var reward = e.detail.value.reward; //赏金
    var divisor = e.detail.value.divisor; //个数
    var service = reward *rate; //服务费
    var sumReward = reward * (rate + 1); //总赏金含服务费
    var balance = this.data.user.balance; //用户余额
    var wxpay = 0; //微信支付
    var balancePay = 0; //余额支付


    if (title == ''){
      wx.showModal({
        title: '提示',
        content: '请输入活动主题',
        showCancel: false
      })
      return false
    }
    if (reward == '' || reward < 1 || reward > 10000) {
      wx.showModal({
        title: '提示',
        content: '赏金必须在1至10000之间',
        showCancel: false
      })
      return false
    }
    if (divisor == '' || divisor < 1 || divisor > 100) {
      wx.showModal({
        title: '提示',
        content: '个数必须在1至100之间',
        showCancel: false
      })
      return false
    }
    if (reward / divisor < 1) {
      wx.showModal({
        title: '提示',
        content: '每个人获得的赏金不得低于1元',
        showCancel: false
      })
      return false
    }



   


    if (sumReward - balance > 0) {
      wxpay = sumReward - balance; //需要微信支付
      balancePay = balance; 
    }
    else{
      balancePay = sumReward;
    }












    var self = this
    self.setData({
      loading: true
    })

    // 此处需要先调用wx.login方法获取code，然后在服务端调用微信接口使用code换取下单用户的openId
    // 具体文档参考https://mp.weixin.qq.com/debug/wxadoc/dev/api/api-login.html?t=20161230#wxloginobject

    app.getUserOpenId(function (err, openid) {
      if (!err) {

        wx.request({
          url: 'https://womendehunli.com/api/wechat/pay',
          data: {
            openid: openid,
            title: title,
            reward: reward,
            divisor: divisor,
            service: service,
            wx_pay: wxpay,
            balance_pay: balancePay
          },
          header:
          {
            'content-type': 'application/x-www-form-urlencoded'
          },
          method: 'POST',
          success: function (res) {




            
            console.log('unified order success, response is:', res)
            var payargs = res.data.payargs
            //console.log(payargs);
            wx.requestPayment({
              'timeStamp': payargs.timeStamp,
              'nonceStr': payargs.nonceStr,
              'package': payargs.package,
              'signType': payargs.signType,
              'paySign': payargs.paySign,
              'success': function (res) { },
              'fail': function (res) { },
              'complete': function (res) { }
            })
            self.setData({
              loading: false
            })
          }
        })
      } else {
        console.log('err:', err)
        self.setData({
          loading: false
        })
      }
    }) 
  },
  

  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function (res) {

  }
})
