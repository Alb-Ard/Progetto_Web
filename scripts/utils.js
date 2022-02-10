function getReadableTimestamp(timestamp) {
    const standardTimestamp = `${timestamp.replace(' ', 'T')}+01:00`;
    const date = new Date(standardTimestamp);
    const currentDate = new Date(Date.now());

    
    let result = "";
    if (currentDate.getFullYear() != date.getFullYear()) {
        result += `${date.toDateString()} at`;
    } else if (currentDate.getMonth() != date.getMonth()) {
        result += `${date.getMonth()} ${date.getDate()} at`;
    } else {
        const dayDifference = currentDate.getDate() - date.getDate();
        switch (dayDifference) {
            case 0:
                result += "Today at";
                break;
            case 1:
                result += "Yesterday at";
                break;
            default:
                result += `${dayDifference} days ago at`;
                break;
        }
    }

    const timeParts = timestamp.split(' ')[1].split(':');
    result += ` ${timeParts[0]}:${timeParts[1]}`;
    return result;
}