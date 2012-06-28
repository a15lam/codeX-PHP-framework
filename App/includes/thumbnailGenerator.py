#! /usr/bin/env phython

import os, Image, sys, string

path  = sys.argv[1]
thumbPath = '/var/www/XPhoto/public/xphoto/app/data/thumbnails/'

thumbWidth = 245
thumbHeight = 162

def getThumbSize(image, maxW, maxH):
        img = Image.open(image)
        imgW, imgH = img.size
        newW = imgW
        newH = imgH

        ratio = imgH/float(imgW)

        if imgW > maxW or imgH > maxH:
                if maxH < maxW:
                        newH = maxH
                        newW = maxH/ratio
                        if newW > maxW:
                                newW = maxW
                                newH = ratio*maxW
                else:
                        newW = maxW
                        newH = ratio*maxW
                        if newH > maxH:
                                newH = maxH
                                newW = maxH/ratio

        return int(newW), int(newH)


list = os.listdir(path)
i=0

allowedExtensions = '.jpg', '.jpeg', '.JPG', '.JPEG'

for l in list :
        mypath, ext = os.path.splitext(l)
        pathSegments = string.split(path, '/')
        dir = pathSegments[len(pathSegments)-2]
        dir = dir.replace(' ', '_')
        if ext in allowedExtensions:
                imgSrc = Image.open(path+l)
                imgDst = imgSrc.resize(getThumbSize(path+l, thumbWidth, thumbHeight), Image.ANTIALIAS)
                imgDst.save(thumbPath+'_'+dir+'_'+l)

