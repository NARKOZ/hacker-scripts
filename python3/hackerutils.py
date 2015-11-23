#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import pathlib
import subprocess

from dotenv import Dotenv


def get_dotenv(filename='.env'):
    return Dotenv(str(pathlib.Path(__file__).parent / filename))


def sh(*args):
    proc = subprocess.Popen(args, stdout=subprocess.PIPE)
    stdout, _ = proc.communicate()
    return stdout


def get_log_path(name):
    path = pathlib.Path(__file__).parent / 'logs' / name
    path.parent.mkdir(parents=True, exist_ok=True)
    return path
