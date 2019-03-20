// pages/make/make.js
Page({
  /**
   * 页面的初始数据
   */
  data: {
    userInput: '',
    imgalist: ['http://t.nongjia365.com.cn/guanli/assets/image/img/datu.jpg'],
    catalogs: [
      {
        "catalogName": "11:00-12:00",
        "select": 1
      },
      {
        "catalogName": "13:00-14:00",
        "select": 2
      },
      {
        "catalogName": "15:00-16:00",
        "select": 3
      },
      {
        "catalogName": "17:00-18:00",
        "select": 4
      },

    ],
    catalogSelect: 0,//判断是否选中

    payment: [
      {
        "point": "2500", 
        "cash": "0",
        "select": 1
      },
      {
        "point": "2500",
        "cash": "30",
        "select": 2
      },
      {
        "point": "0",
        "cash": "30",
        "select": 3
      },
    ],
    paymentSelect: 1,//判断是否选中
    // input默认是1 
    num: 0,
    // 使用data数据对象设置样式名 
    minusStatus: 'disabled'
  },
  //评价图片预览
  previewImage: function (e) {
    var current = e.target.dataset.src;
    wx.previewImage({
      current: current, // 当前显示图片的http链接  
      urls: this.data.imgalist // 需要预览的图片http链接列表  
    })
  },
//时间切换选项
  chooseCatalog: function (data) {
    var that = this;
    that.setData({//把选中值放入判断值
      catalogSelect: data.currentTarget.dataset.select
    })
  },
  //支付切换选项
  paymentCatalog: function (data) {
    var that = this;
    that.setData({//把选中值放入判断值
      paymentSelect: data.currentTarget.dataset.select
    })
  },
  /* 点击减号 */
  bindMinus: function () {
    var num = this.data.num;
    // 如果大于1时，才可以减 
    if (num > 0) {
      num--;
    }
    // 只有大于一件的时候，才能normal状态，否则disable状态 
    var minusStatus = num <= 0 ? 'disabled' : 'normal';
    // 将数值与状态写回 
    this.setData({
      num: num,
      minusStatus: minusStatus
    });
  },
  /* 点击加号 */
  bindPlus: function () {
    var num = this.data.num;
    // 不作过多考虑自增1 
    num++;
    // 只有大于一件的时候，才能normal状态，否则disable状态 
    var minusStatus = num < 0 ? 'disabled' : 'normal';
    // 将数值与状态写回 
    this.setData({
      num: num,
      minusStatus: minusStatus
    });
  },
  /* 输入框事件 */
  bindManual: function (e) {
    var num = e.detail.value;
    // 将数值与状态写回 
    this.setData({
      num: num
    });
  },

/*清除输入框数值 */
  clearInput: function () {
    this.setData({
      userInput: ''
    });
  },
  bindKeyInput: function (e) {
    this.setData({
      userInput: e.detail.value
    });
  },
  bindtitl: function (e) {
      wx.showToast({
        title: '微信支付成功',
        icon: 'success',

        success: function (res) {
          url:'../pages/index/index'
        }
      })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
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