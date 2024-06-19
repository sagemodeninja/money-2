// Written by Gary Antier 2020
// Last updated: April 11, 2021
// Current version 1.2.0.0

const FullMonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const AbbrMonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
const FullDaysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
const AbbrDaysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const MinutesInHour = 60;
const SecondsInMinute = 60;
const MillisecondsInSecond = 1000;

class TimeConstantsBase {
    get MillisecondsInASecond() {
        return 1000;
    }

    get SecondsInAMinute() {
        return 60;
    }

    get MillisecondsInAMinute() {
        let milli = this.MillisecondsInASecond * this.SecondsInAMinute;
        return milli;
    }

    get MinutesInAnHour() {
        return 60;
    }

    get MillisecondsInAnHour() {
        let milli = this.MillisecondsInAMinute * this.MinutesInAnHour;
        return milli;
    }

    get HoursInADay() {
        return 24;
    }

    get MillisecondsInADay() {
        let milli = this.MillisecondsInAnHour * this.HoursInADay;
        return milli;
    }

    get DaysInAYear() {
        return 365;
    }
}

const TimeConstants = new TimeConstantsBase();

class TimeSpan {
    constructor(milli) {
        this.milli = milli;
        this.totalSeconds = Math.floor(milli / TimeConstants.MillisecondsInASecond);
        this.totalMinutes = Math.floor(milli / TimeConstants.MillisecondsInAMinute);
        this.totalHours = Math.floor(milli / TimeConstants.MillisecondsInAnHour);
        this.totalDays = Math.floor(milli / TimeConstants.MillisecondsInADay);

        this.seconds = this.totalSeconds % TimeConstants.SecondsInAMinute;
        this.minutes = this.totalMinutes % TimeConstants.MinutesInAnHour;
        this.hours = this.totalHours % TimeConstants.HoursInADay;
        this.days = this.totalDays % TimeConstants.DaysInAYear;
    }

    toString() {
        let duration = "";

        if (this.days > 0) {
            duration += `${this.days}d `;
        }

        if (this.hours > 0) {
            duration += `${this.hours}h `;
        }

        if (this.minutes > 0) {
            duration += `${this.minutes}m `;
        }

        if (this.seconds > 0) {
            duration += `${this.seconds}s`;
        } else if (duration == "") {
            duration = "...";
        }

        return duration;
    }
}

class DateTime {
    constructor(date, offset) {
        this._date = date;
        this.offset = offset;
    }

    static parse(dateTime, offset = 0) {
        if (dateTime) {

            let irregularFormatRegex = /(\/Date\()(.*)(\)\/)/i;
            if (typeof dateTime === "string" && irregularFormatRegex.test(dateTime)) {
                let match = irregularFormatRegex.exec(dateTime);
                dateTime = parseInt(match[2]);
            }

            let milli = typeof dateTime === "number" ? dateTime : Date.parse(dateTime);

            if (isNaN(milli)) {
                milli = 1;
            }

            // UTC offset...
            offset = offset * TimeConstants.MillisecondsInAnHour;
            milli += offset;

            let date = new Date(milli);
            return new DateTime(date, offset);
        } else {
            return null;
        }
    }

    static now() {
        return new DateTime(new Date(), 8);
    }

    get year() {
        return this._date.getFullYear();
    }

    get month() {
        return this._date.getMonth();
    }

    get date() {
        return this._date.getDate();
    }

    get day() {
        return this._date.getDay();
    }

    get hour() {
        return this._date.getHours();
    }

    get minutes() {
        return this._date.getMinutes();
    }

    get seconds() {
        return this._date.getSeconds();
    }

    get time() {
        return this._date.getTime();
    }

    static difference(start, end) {
        var timeDiff = end.getTime() - start.getTime();
        return new TimeSpan(timeDiff);
    }

    difference(secondDate) {
        let diff = this.time - secondDate.time;
        return new TimeSpan(diff);
    }

    addDays(days) {
        let newDate = new Date(this._date);
        newDate.setDate(this.date + days);

        return new DateTime(newDate, this.offset);
    }

    addYears(years) {
        let newDate = new Date(this._date);
        newDate.setFullYear(this.year + years);

        return new DateTime(newDate, this.offset);
    }

    toString(format = "yyyy/MM/dd HH:mm:ss") {

        if (!this._date)
            return null;

        let year = this.year.toString();
        let subYear = year.padStart(2, '0').slice(-2);
        let month = this.month;
        let day = this.day;
        let date = this.date;
        let hour = this.hour;
        let _12Hour = hour < 13 ? hour : hour - 12;
        let isAM = hour < 12;
        let minutes = this.minutes;
        let seconds = this.seconds;

        // Day...
        format = format.replace(/dd/g, date.toString().padStart(2, '0'));
        format = format.replace(/d/g, date);

        // 24-Hour...
        format = format.replace(/HH/g, hour.toString().padStart(2, '0'));
        format = format.replace(/H/g, hour);

        // 12-Hour...
        format = format.replace(/hh/g, _12Hour.toString().padStart(2, '0'));
        format = format.replace(/h/g, _12Hour);

        // Minutes...
        format = format.replace(/mm/g, minutes.toString().padStart(2, '0'));
        format = format.replace(/m/g, minutes);

        // Seconds...
        format = format.replace(/ss/g, seconds.toString().padStart(2, '0'));
        format = format.replace(/s/g, seconds);

        // Year...
        format = format.replace(/yyyyy/g, year.padStart(5, '0'));
        format = format.replace(/yyyy/g, year.padStart(4, '0'));
        format = format.replace(/yyy/g, year.padStart(3, '0'));
        format = format.replace(/yy/g, subYear);
        format = format.replace(/y/g, parseInt(subYear));

        // Month in words...
        format = format.replace(/MMMM/g, "####");
        format = format.replace(/MMM/g, "###");

        // Month in number...
        let _month = month + 1;
        format = format.replace(/MM/g, _month.toString().padStart(2, '0'));
        format = format.replace(/M/g, _month);

        // AM/PM...
        format = format.replace(/tt/g, isAM ? "AM" : "PM");
        format = format.replace(/t/g, isAM ? "A" : "P");

        // Day of week...
        format = format.replace(/dddd/g, FullDaysOfWeek[day]);
        format = format.replace(/ddd/g, AbbrDaysOfWeek[day]);

        // Month in words...
        format = format.replace(/####/g, FullMonth[month]);
        format = format.replace(/###/g, AbbrMonth[month]);

        return format;
    }
}