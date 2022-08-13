import face_recognition as fr
import os
import cv2
import face_recognition
import time
import urllib.request
import numpy as np
import time
import threading

import concurrent.futures
from time import sleep



import face_recognition as fr
import os
import cv2
import face_recognition
import time
import urllib.request
import numpy as np
import time

import multiprocessing
from time import sleep






URL="http://192.168.43.1:8080/shot.jpg"

def get_encoded_faces():
    """
    looks through the faces folder and encodes all
    the faces

    :return: dict of (name, image encoded)
    """
    encoded = {}

    for dirpath, dnames, fnames in os.walk("./faces"):
        for f in fnames:
            if f.endswith(".jpg") or f.endswith(".png"):
                face = fr.load_image_file("faces/" + f)
                encoding = fr.face_encodings(face)[0]
                encoded[f.split(".")[0]] = encoding

    return encoded


def unknown_image_encoded(img):
    """
    encode a face given the file name
    """
    face = fr.load_image_file("faces/" + img)
    encoding = fr.face_encodings(face)[0]

    return encoding



faces = get_encoded_faces()
faces_encoded = list(faces.values())
known_face_names = list(faces.keys())


while True:

    img_arr = np.array(bytearray(urllib.request.urlopen(URL).read()), dtype=np.uint8)
    img = cv2.imdecode(img_arr, -1)
    img=cv2.resize(img,(0,0),fx=0.5,fy=0.5)



        # img = cv2.imread(im, 1)

        # img = img[:,:,::-1]

    face_locations = face_recognition.face_locations(img)
    unknown_face_encodings = face_recognition.face_encodings(img, face_locations)

    face_names = []

    for face_encoding in unknown_face_encodings:
        # See if the face is a match for the known face(s)
        matches = face_recognition.compare_faces(faces_encoded, face_encoding)
        name = "Unknown"

        # use the known face with the smallest distance to the new face
        face_distances = face_recognition.face_distance(faces_encoded, face_encoding)
        best_match_index = np.argmin(face_distances)
        if matches[best_match_index]:
            name = known_face_names[best_match_index]

        face_names.append(name)
        print(name)


        # store end time

        # total time taken

        for (top, right, bottom, left), name in zip(face_locations, face_names):
            # Draw a box around the face
            cv2.rectangle(img, (left - 20, top - 20), (right + 20, bottom + 20), (255, 0, 0), 1)

            # Draw a label with a name below the face
            #cv2.rectangle(img, (left - 25, top + 80), (right + 37, bottom + 40), (255, 0, 0), cv2.FILLED)
            font = cv2.FONT_HERSHEY_TRIPLEX
            # cv2.putText(img, name, (left -20, bottom + 20), font, 1.0, (255, 255, 255),2)
            cv2.putText(img, name, (left - 25, bottom + 35), font, 0.45, (255, 255, 255), 1)

    cv2.imshow('IPWebcam', img)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break



    # Display the resulting image