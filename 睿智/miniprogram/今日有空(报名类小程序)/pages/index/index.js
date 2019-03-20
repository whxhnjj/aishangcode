// pages/detail/detail.js
Page({

  /**
   * 页面的初始数据
   */
  data: { 
    movies: [
      { url: 'http://t.nongjia365.com.cn/guanli/assets/image/img/lunbo.jpg' },
      { url: 'http://t.nongjia365.com.cn/guanli/assets/image/img/lunbo2.jpg' },
      { url: 'http://t.nongjia365.com.cn/guanli/assets/image/img/lunbo.jpg' },
      { url: 'http://t.nongjia365.com.cn/guanli/assets/image/img/lunbo2.jpg' },
    ],
    functions: [
      {
        url: '../../pages/images/i01.png',
        name: '微旅行',
        id: '01'
      },
      {
        url: '../../pages/images/i02.png',
        name: '微课程',
        id: '02'
      },
      {
        url: '../../pages/images/i03.png',
        name: '亲子',
        id: '03'
      },
      {
        url: '../../pages/images/i04.png',
        name: '超值体验',
        id: '04'
      },
      {
        url: '../../pages/images/i05.png',
        name: '温泉',
        id: '05'
      },
      {
        url: '../../pages/images/i06.png',
        name: '滑雪',
        id: '06'
      },
      {
        url: '../../pages/images/i05.png',
        name: '健身',
        id: '07'
      },
      {
        url: '../../pages/images/i06.png',
        name: '娱乐',
        id: '08'
      },
    ],
    goods: [
      {
        url: 'http://p1.meituan.net/wedding/5c683d257d0a418c146308b455bb5b582651471.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '热烈如初',
        price: '13800',
        oldprice: '19800',
        sell: '5',
        address: '二环路东五段万达广场8单元2101(近成仁公交站)',
        km: '1.1km'
      },
      {
        url: 'http://p1.meituan.net/wedding/adf460e1e88714cb30e118387de0b09e3536225.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '全包好超值无敌到爆宇宙套餐',
        price: '8800',
        oldprice: '10800',
        sell: '20',
        address: '东大街芷泉段88号时代豪庭(香格里拉酒店)',
        km: '1.8km'
      },
      {
        url: 'http://p0.meituan.net/wedding/4972ddf9c2067c193f6408f006f818c02213163.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '林中奇缘',
        price: '15800',
        oldprice: '20800',
        sell: '15',
        address: '总府路46号1-4楼(盐市口红旗商场斜对面)',
        km: '2.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/8a40a46c24c3f812586853aa5d5cb56d3134895.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '清新云系婚礼经典款',
        price: '12900',
        oldprice: '15800',
        sell: '25',
        address: '天府新区益州大道588号益州国际写字楼10楼',
        km: '3.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/5c683d257d0a418c146308b455bb5b582651471.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '热烈如初',
        price: '13800',
        oldprice: '19800',
        sell: '5',
        address: '二环路东五段万达广场8单元2101(近成仁公交站)',
        km: '1.1km'
      },
      {
        url: 'http://p1.meituan.net/wedding/adf460e1e88714cb30e118387de0b09e3536225.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '全包好超值无敌到爆宇宙套餐',
        price: '8800',
        oldprice: '10800',
        sell: '20',
        address: '东大街芷泉段88号时代豪庭(香格里拉酒店)',
        km: '1.8km'
      },
      {
        url: 'http://p0.meituan.net/wedding/4972ddf9c2067c193f6408f006f818c02213163.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '林中奇缘',
        price: '15800',
        oldprice: '20800',
        sell: '15',
        address: '总府路46号1-4楼(盐市口红旗商场斜对面)',
        km: '2.4km'
      },
      {
        url: 'http://p1.meituan.net/wedding/8a40a46c24c3f812586853aa5d5cb56d3134895.jpg%40640w_480h_0e_1l%7Cwatermark%3D0',
        name: '清新云系婚礼经典款',
        price: '12900',
        oldprice: '15800',
        sell: '25',
        address: '天府新区益州大道588号益州国际写字楼10楼',
        km: '3.4km'
      }
    ],
  },
  fucClick(event) {
    const id = event.currentTarget.dataset.id;
    console.log(id);
    wx.navigateTo({
      url: '../list/list',
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