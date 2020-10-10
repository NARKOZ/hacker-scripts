#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import pathlib as pth
import subprocess as sbp

from dotenv import Dotenv


def get_dotenv(filename='.env'):
    return Dotenv(str(pth.Path(__file__).parent / filename))


def sh(*args):
    proc = sbp.Popen(args, stdout=sbp.PIPE)
    stdout, _ = proc.communicate()
    return stdout


def get_log_path(name):
    path = pth.Path(__file__).parent / 'logs' / name
    path.parent.mkdir(parents=True, exist_ok=True)
    return path
