
/* remote qml file load sample */

import QtQuick 1.0
//![3]

//![1]
Rectangle {
    id: pagess
    width: 680; height: 450;
    border.color: "black"; 
    color: "white"; 
    radius: 44;


    Text {
        id: _tag1
        text: "Hello remote test load qml file."
        y: 44
        anchors.horizontalCenter: page.horizontalCenter
        font.pixelSize: 25; 
        font.bold: true;
        font.letterSpacing : 8.4;
        style: Text.Raised; styleColor: "gray";
    }
    
     Text {
        id: _tag2
        text: "Can You xslt transform?  doc apps?"
        y: 88
        anchors.horizontalCenter: page.horizontalCenter
        font.pixelSize: 25; 
        font.bold: true;
        font.letterSpacing : 8.4;
        style: Text.Raised; styleColor: "gray";
    }

}

