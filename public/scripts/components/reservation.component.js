class ReservationComponent {
	constructor() {
		this.dateFrom = null;
		this.dateTo = null;
		this.adultsAmount = null;
		this.childrenAmount = null;
		this.observation = null;
		this.roomsAmount = null;
		this.roomTypes = [];

		this.nameAndLastName = null;
		this.email = null;
		this.phone = null;
	}

	setDateFrom(date) {
		this.dateFrom = date;
	}
	
	getDateFrom() {
		return this.dateFrom;
	}
	
	setDateTo(date) {
		this.dateTo = date;
	}
	
	getDateTo() {
		return this.dateTo;
	}

	setAdultsAmount(amount) {
		this.adultsAmount = amount;
	}

	getAdultsAmount() {
		return this.adultsAmount;
	}

	setChildrenAmount(amount) {
		this.childrenAmount = amount;
	}

	getChildsAmount() {
		return this.childrenAmount
	}

	setRoomsAmount(amount) {
		this.roomsAmount = amount;
	}

	getRoomsAmount() {
		return this.roomsAmount;
	}

	setRoomTypes(roomTypeId) {
		this.roomTypeId = roomTypeId;
	}

	resetRoomTypes() {
		this.roomTypes = [];
	}

	setObservation(observation) {
		this.observation = observation;
	}

	setUserName(name) {
		this.nameAndLastName = name;
	}

	getUserName() {
		return this.nameAndLastName;
	}

	setUserEmail(email) {
		this.email = email;
	}

	getUserEmail() {
		return this.email;
	}

	setUserPhone(phone) {
		this.phone = phone;
	}

	getUserPhone() {
		return this.phone;
	}

	async getReservationPayment(paymentMethod, callback = () => {}) {
		Utils.makeAjaxCall({
			method: 'POST',
			url: '/reservation/payment',
			body: {
				paymentMethod,
				dateFrom: this.dateFrom,
				dateTo: this.dateTo,
				adultsAmount: this.adultsAmount,
				childrenAmount: this.childrenAmount,
				roomTypeId: this.roomTypeId,
				userName: this.nameAndLastName,
				userEmail: this.email,
				userPhone: this.phone
			}, callback
		})
	}
}