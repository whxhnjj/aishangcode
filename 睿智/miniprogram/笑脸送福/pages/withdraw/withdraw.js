// pages/withdrawals/withdrawals.js
//获取应用实例
const app = getApp()
var bal = 0
Page({
  /**
   * 页面的初始数据
   */
  data: {
    money:0,
    alert: '不能超过10',
    alertclass: 'hide',
  },

  /**
   * 生命周期函数--监听页面加载
   */

  onLoad: function (options) {
    var that = this;
    //获取余额
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
              bal = res.data.data.balance
              console.log(bal);
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
 
  input: function (e) {
    var money = e.detail.value
   console.log(money)
   if (e.currentTarget.id == 'cash') {
     //不小于10000
     if (money > bal) {
       this.setData({
         cash: bal,
         alert: '总赏金不能超过'+bal+'元',
         alertclass: 'show'
       })
       return bal;
     } else{
       this.setData({
         alert: '',
         alertclass: 'hide'
       })
     }

     return money;
   }

  },
  /**提现 */
  formSubmit:function(e){
    var money = e.detail.value.cash
   
    if (money =='') {
        wx.showModal({
          title: '提示',
          content: '请输入有效提现金额',
          showCancel:false
        })
        return false
    }
    if (money < 1 && money!='') {
      wx.showModal({
        title: '提示',
        content: '提现金额不能低于1元',
        showCancel: false
      })
      return false
    }

    //请求提现
    app.getUserOpenId(function (err, openid) {
      if (!err) {

        wx.showLoading({ title: '提现中' });
        wx.request({
          url: 'https://womendehunli.com/api/user/withdraw',
          data: {
            openid: openid,
            money: e.detail.value.cash,
          },
          method: 'POST',
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              wx.showLoading({ title: '提现成功' });
              wx.redirectTo({
                url: '../../pages/withdraw/withdraw'
              })
            } else {
              wx.showToast({ title: '提现错误' });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '提现失败' });
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
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})